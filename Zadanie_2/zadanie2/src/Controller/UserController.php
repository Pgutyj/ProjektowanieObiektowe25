<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Form\UserPasswordType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Doctrine\ORM\EntityManagerInterface;


#[Route(path: '/profile')]
class UserController extends AbstractController
{

    private UserPasswordHasherInterface $passwordHasher;
    private $security;
    private CsrfTokenManagerInterface $csrfTokenManager;

    public function __construct(UserPasswordHasherInterface $passwordHasher, Security $security, CsrfTokenManagerInterface $csrfTokenManager)
    {
        $this->passwordHasher = $passwordHasher;
        $this->security = $security;
        $this->csrfTokenManager = $csrfTokenManager;
    }


    #[Route(name: 'app_profile', methods: 'GET|POST')]
    public function index(): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $user = $this->security->getUser();

        return $this->render(
            'user/profile.html.twig',
            ['user' => $user]
        );
    }


    #[Route(path: '/{id}/profile_edit', name: 'profile_edit', requirements: ['id' => '[1-9]\d*'], methods: 'GET|POST')]
    public function edit(Request $request, EntityManagerInterface $em, User $user): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $form = $this->createForm(
            UserType::class,
            $user,
            [
                'method' => 'POST',
                'action' => $this->generateUrl('profile_edit', ['id' => $user->getId()]),
            ]
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $csrfToken = $this->csrfTokenManager->getToken('form_intention')->getValue();

            $em->persist($user);
            $em->flush();
            $this->addFlash('success','Pomyślnie edytowano');

            return $this->redirectToRoute('app_profile');
        }

        return $this->render(
            'user/edit.html.twig',
            [
                'user' => $user,
                'form' => $form->createView(),
            ]
        );
    }

    #[Route(path: '/{id}/password_edit', name: 'password_edit', requirements: ['id' => '[1-9]\d*'], methods: 'GET|POST')]
    public function editPassword(Request $request, CsrfTokenManagerInterface $csrfTokenManager, EntityManagerInterface $em, User $user): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $form = $this->createForm(
            UserPasswordType::class,
            $user,
            [
                'method' => 'POST',
                'action' => $this->generateUrl('password_edit', ['id' => $user->getId()]),
            ]
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $password = $user->getPassword();
            $user->setPassword(
                $this->passwordHasher->hashPassword(
                    $user,
                    $password
                )
            );
            $em->persist($user);
            $em->flush();

            $csrfTokenManager->refreshToken('form_intention');

            $this->addFlash('success', 'Zostałeś pomyślnie zalogowany!');


            return $this->redirectToRoute('app_profile');
        }

        return $this->render(
            'user/editPassword.html.twig',
            [
                'user' => $user,
                'form' => $form->createView(),
            ]
        );
    }


    #[Route('/{id}/delete', name: 'user_delete', requirements: ['id' => '[1-9]\d*'], methods: 'GET|POST')]
    public function delete(Request $request, EntityManagerInterface $em, User $user): Response
    {
        $form = $this->createForm(
            UserType::class,
            $user,
            [
                'method' => 'POST',
                'action' => $this->generateUrl('user_delete', ['id' => $user->getId()]),
            ]
        );
        $user1 = $this->getUser();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($this->isCsrfTokenValid('user_delete' . $user->getId(), $request->request->get('_token'))){
                $em->remove($user1);
                $em->flush();

                $this->addFlash(
                'success',
                'Pomyślnie usunięto'
            );
            }
            else{
                $this->addFlash('error', 'Nieprawidłowy token CSRF.');
            }
            return $this->redirectToRoute('app_login');
        }

        return $this->render(
            'user/delete.html.twig',
            [
                'form' => $form->createView(),
                'user' => $user,
            ]
        );
    }


    #[Route(
        '/{id}',
        name: 'user_show',
        requirements: ['id' => '[1-9]\d*'],
        methods: 'GET',
    )]
    public function show(User $user): Response
    {
        return $this->render('user/profile.html.twig', ['user' => $user]);
    }
}