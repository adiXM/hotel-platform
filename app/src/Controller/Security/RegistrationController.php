<?php

namespace App\Controller\Security;

use App\Entity\Customer;
use App\Form\Frontend\RegistrationFormType;
use App\Service\EntityManagerServices\CustomerManagerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class RegistrationController extends AbstractController
{

    public function __construct(
        private readonly CustomerManagerInterface $customerManagerService
    )
    {
    }

    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        $customer = new Customer();
        $form = $this->createForm(RegistrationFormType::class, $customer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Customer $customerData */
            $customerData = $form->getData();

            $plainPassword = $form->get('plainPassword')->getData();

            if($plainPassword !== null) {
                $customerData->setPassword($this->customerManagerService->getHashPassword($customerData, $plainPassword));
            }

            $this->customerManagerService->updateUser($customerData);

            $this->addFlash('notice', 'Your changes were saved!');

            return $this->redirectToRoute('app_login');
        }

        return $this->render('pages/register/index.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
}