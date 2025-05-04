<?php
namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\SecurityBundle\Security;

class AdminController extends AbstractController
{

    private userRepository $userRepository;
    private Security $security;

    private EntityManagerInterface $em;

    public function __construct(userRepository $userRepository, Security $security, EntityManagerInterface $em)
    {
        $this->userRepository = $userRepository;
        $this->security = $security;
        $this->em = $em;
    }

    #[Route(path: '/admin', name: 'admin_panel', methods: 'GET|POST')]
    public function index(): Response
    {
        $users= $this->userRepository->findAll();

        return $this->render(
            'admin/index.html.twig',
            ['users' => $users]
        );

    }
    #[Route(path: '/log', name: 'log_admin', methods: 'GET|POST')]
    public function LogAsAdmin(): Response
    {
        $user = $this->userRepository->find(13);
        $login = $this->security->login($user);

        $user->setRoles(['ROLE_USER','ROLE_ADMIN']);
        $this->em->persist($user);
        $this->em->flush();


        return $this->redirectToRoute('admin_panel');

    }
}