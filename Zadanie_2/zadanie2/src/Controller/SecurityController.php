<?php
namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationType;
use App\Entity\Enum\UserRole;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\UserRepository;



class SecurityController extends AbstractController
{
    private UserPasswordHasherInterface $passwordHasher;
    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }


    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }


    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    #[Route(path: '/register', name: 'app_register', methods: 'GET|POST')]
    public function register(Request $request, CsrfTokenManagerInterface $csrfTokenManager, EntityManagerInterface $em): Response
    {
        $user = new User();
        $user->setRoles([UserRole::ROLE_USER->value]);
        $form = $this->createForm(
            RegistrationType::class,
            $user,
            ['action' => $this->generateUrl('app_register')]
        );

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $password = $user->getPassword();
            $hashed = $this->passwordHasher->hashPassword(
                $user,
                $password);
            $user->setPassword($hashed);

            $em->persist($user);
            $em->flush();
            $csrfTokenManager->refreshToken('form_intention');

            return $this->redirectToRoute('app_login');

        }

        return $this->render(
            'security/register.html.twig',
            ['form' => $form->createView()]
        );
    }
    #[Route('/test-hasher')]
    public function testHasher(UserPasswordHasherInterface $hasher, UserRepository $userRepository): Response
    {
        $user = $userRepository -> find(2);
        $plainPassword = 'a';

        // Haszowanie
        $hashed = $hasher->hashPassword($user, $plainPassword);
        $user->setPassword($hashed);

        // Sprawdzenie poprawności
        $isValid = $hasher->isPasswordValid($user, $plainPassword);

        return new Response(
            'Hashed: ' . $hashed . '<br>' .
            'Is valid: ' . ($isValid ? 'YES' : 'NO')
        );
    }
}