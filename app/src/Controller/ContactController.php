<?php

namespace App\Controller;

use App\Form\Frontend\ContactFormType;
use App\Service\MailerServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{

    public function __construct(
        private readonly MailerServiceInterface $mailerService
    )
    {
    }

    #[Route('/contact', name: 'app_contact')]
    public function about(Request $request): Response
    {
        $contactForm = $this->createForm(ContactFormType::class);

        $contactForm->handleRequest($request);

        if($contactForm->isSubmitted() && $contactForm->isValid()) {

            $contactFormData = $contactForm->getData();

            if((int)$contactFormData['human'] !== 4) {
                $contactForm->get('human')->addError(new FormError('The answer is not correct.'));
            } else {
                $this->addFlash('success', 'Your message has been sent');
                $this->mailerService->sendEmail('adrianmarian906@gmail.com',
                    sprintf('A new message from %s', $contactFormData['name']),
                    sprintf('Name: %s, Email: %s, Message: %s', $contactFormData['name'], $contactFormData['email'], $contactFormData['message']));

                return $this->redirectToRoute('app_contact');
            }

        }

        return $this->render('pages/custom_pages/contact.html.twig', [
            'form' => $contactForm->createView()
        ]);
    }
}