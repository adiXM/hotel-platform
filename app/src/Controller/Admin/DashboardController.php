<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractController
{

    #[Route('/admin', name: 'admin_dashboard')]
    public function index(): Response
    {
        // Render Page

        $hasAccess = $this->isGranted('ROLE_ADMIN');
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        return $this->render('admin/pages/dashboard/index.html.twig');

    }

    #[Route('/admin/settings', name: 'admin_settings')]
    public function index_settings(): Response
    {
        // Render Page

        $hasAccess = $this->isGranted('ROLE_ADMIN');
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        return $this->render('admin/pages/dashboard/settings.html.twig');

    }

}