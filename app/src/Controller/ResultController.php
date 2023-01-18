<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ResultController extends AbstractController
{

    #[Route('/confirm_booking', name: 'app_confirm_booking')]
    public function index(Request $request): Response
    {
        return $this->render('pages/custom_pages/confirm_booking.html.twig', [
            'message' => 'Your booking is confirmed!'
        ]);
    }

}