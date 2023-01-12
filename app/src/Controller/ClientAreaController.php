<?php

namespace App\Controller;

use App\Entity\Customer;
use App\Form\Customer\CustomerFormType;
use App\Form\Frontend\Clientarea\ProfileFormType;
use App\Form\Frontend\RegistrationFormType;
use App\Service\EntityManagerServices\CustomerManagerInterface;
use App\Service\Frontend\DataManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ClientAreaController  extends AbstractController
{
    public function __construct(
        private readonly DataManagerInterface $dataManager,
        private readonly CustomerManagerInterface $customerManagerService)
    {
    }

    #[Route('/clientarea', name: 'app_clientarea')]
    public function index(Request $request): Response
    {
        return $this->render('pages/homepage/index.html.twig', [
            'rooms' => $this->dataManager->getHomepageRooms()
        ]);
    }

    #[Route('/clientarea/profile', name: 'app_clientarea_profile')]
    public function profile(Request $request): Response
    {
        $customer = $this->getUser();
        $form = $this->createForm(ProfileFormType::class, $customer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Customer $customerData */
            $customerData = $form->getData();

            $plainPassword = $form->get('plainPassword')->getData();

            if($plainPassword !== null) {
                $customerData->setPassword($this->customerManagerService->getHashPassword($customerData, $plainPassword));
            }

            $this->customerManagerService->updateUser($customerData);

            $this->addFlash('success', 'Your changes were saved!');

            return $this->redirectToRoute('app_clientarea_profile');
        }

        return $this->render('pages/clientarea/profile/index.html.twig', [
            'profileForm' => $form->createView(),
        ]);
    }
}