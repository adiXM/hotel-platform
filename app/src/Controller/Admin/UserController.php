<?php

namespace App\Controller\Admin;

use App\Service\UserServiceManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{

    public function __construct(private UserServiceManager $userServiceManager)
    {
    }

    #[Route('/admin/users', name: 'admin_users')]
    public function index(): Response
    {
        // Render Page

        $hasAccess = $this->isGranted('ROLE_ADMIN');
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        return $this->render('admin/pages/dashboard/index.html.twig', [
            'users' => $this->userServiceManager->getUserList()
        ]);

    }

}