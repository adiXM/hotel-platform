<?php

namespace App\Controller;

use App\Form\Frontend\Homepage\SearchFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SearchController extends AbstractController
{
    #[Route('/search', name: 'app_search_result')]
    public function index(Request $request): Response
    {
        dd($request->query->all());
        return $this->render('pages/search/search_result.html.twig', [
            'rooms' => []
        ]);
    }
}