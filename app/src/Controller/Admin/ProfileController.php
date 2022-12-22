<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Form\UserPasswordProfileType;
use App\Form\UserProfileType;
use App\Service\UserManagerService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class ProfileController extends AbstractController
{

    public function __construct(
        private readonly UserManagerService $userService
    ) {

    }
    #[Route('/admin/profile', name: 'admin_profile')]
    public function index(Request $request): Response
    {
        $hasAccess = $this->isGranted('ROLE_ADMIN');
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $user = $this->getUser();

        $userProfileForm = $this->createForm(UserProfileType::class, $user);

        $userProfileForm->handleRequest($request);

        if ($userProfileForm->isSubmitted() && $userProfileForm->isValid()) {

            /** @var User $userData */
            $userData = $userProfileForm->getData();

            $plainPassword = $userProfileForm->get('plainPassword')->getData();

            if($plainPassword !== null) {
                $userData->setPassword($this->userService->getHashPassword($userData, $plainPassword));
            }

            $this->userService->updateUser($userData);
            $this->addFlash('notice', 'Your changes were saved!');

            return $this->redirectToRoute('admin_profile');
        }

        return $this->render('admin/pages/profile/index.html.twig', [
            'user_profile_form' => $userProfileForm
        ]);
    }
}