<?php

namespace App\Controller;

use App\Form\Frontend\ContactFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{

    /*
     * TODO: de facut pagina de contact, de facut mail service si de configurat
     * De vazut in admin ce mai trebuie facut
     * Pe frontend de reparat ce mai e pe acolo
     * de creat pagina clientarea
     */

    public function __construct()
    {
    }

    #[Route('/contact', name: 'app_contact')]
    public function about(Request $request): Response
    {
        $contactForm = $this->createForm(ContactFormType::class);

        $contactForm->handleRequest($request);

        if($contactForm->isSubmitted() && $contactForm->isValid()) {

            $contactFormData = $contactForm->getData();

            $this->addFlash('success', 'Your message has been sent');

            return $this->redirectToRoute('app_contact');
        }

        return $this->render('pages/custom_pages/contact.html.twig', [
            'form' => $contactForm->createView()
        ]);
    }
}