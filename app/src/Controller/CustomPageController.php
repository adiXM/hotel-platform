<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CustomPageController extends AbstractController
{

    #[Route('/confirm_booking', name: 'app_confirm_booking')]
    public function index(Request $request): Response
    {
        return $this->render('pages/custom_pages/confirm_booking.html.twig', [
            'message' => 'Your booking is confirmed!'
        ]);
    }

    #[Route('/about_project', name: 'app_about_project')]
    public function aboutProject(Request $request): Response
    {
        return $this->render('pages/custom_pages/about_project.html.twig', [

        ]);
    }

    #[Route('/about', name: 'app_about')]
    public function about(Request $request): Response
    {
        return $this->render('pages/custom_pages/about.html.twig', [
            'message' => 'Your booking is confirmed!'
        ]);
    }
}