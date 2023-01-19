<?php

namespace App\Controller\Security;

use App\Form\Frontend\LoginFormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class LoginController extends AbstractController
{
    #[Route('/login', name: 'app_login')]
    public function index(Request $request, AuthenticationUtils $authenticationUtils): Response
    {

        if($this->isGranted('IS_AUTHENTICATED_FULLY')) {
            //return $this->redirectToRoute('admin_dashboard');
        }

        $error = $authenticationUtils->getLastAuthenticationError();

        $lastUsername = $authenticationUtils->getLastUsername();


        return $this->render('pages/login/index.html.twig', [
            'last_username'   => $lastUsername,
            'error'           => $error
        ]);
    }

    #[Route('/logout', name: 'app_logout')]
    public function logout(): Response
    {
        return $this->redirectToRoute('app_homepage');
    }
}
