<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Form\UserProfileType;
use App\Service\TableService;
use App\Service\UserManagerService;
use Omines\DataTablesBundle\Adapter\ArrayAdapter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Table\UserTableType;

class UserController extends AbstractController
{

    public function __construct(
        private readonly UserManagerService $userServiceManager,
        private readonly TableService $tableService)
    {
    }

    #[Route('/admin/users', name: 'admin_users')]
    public function index(Request $request): Response
    {
        $hasAccess = $this->isGranted('ROLE_ADMIN');
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $table = $this->tableService->createTableType(UserTableType::class)
            ->createAdapter(ArrayAdapter::class, $this->userServiceManager->getUserList())
            ->handleRequest($request);;

        if ($table->isCallback()) {
            return $table->getResponse();
        }

        $user = new User();

        $userProfileForm = $this->createForm(UserProfileType::class, $user);

        $userProfileForm->handleRequest($request);

        if ($userProfileForm->isSubmitted() && $userProfileForm->isValid()) {

            /** @var User $userData */
            $userData = $userProfileForm->getData();

            $plainPassword = $userProfileForm->get('plainPassword')->getData();

            if($plainPassword !== null) {
                $userData->setPassword($this->userServiceManager->getHashPassword($userData, $plainPassword));
            }

            $this->userServiceManager->updateUser($userData);

            $this->addFlash('notice', 'Your changes were saved!');

            return $this->redirectToRoute('admin_users');
        }

        return $this->render('admin/pages/users/index.html.twig', [
            'form' => $userProfileForm->createView(),
            'userTable' => $table
        ]);
    }

    #[Route('/admin/users/{id}', name: 'admin_edit_users')]
    public function edit(User $user, Request $request)
    {
        $hasAccess = $this->isGranted('ROLE_ADMIN');
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $userProfileForm = $this->createForm(UserProfileType::class, $user);

        $userProfileForm->handleRequest($request);

        if ($userProfileForm->isSubmitted() && $userProfileForm->isValid()) {

            /** @var User $userData */
            $userData = $userProfileForm->getData();

            $plainPassword = $userProfileForm->get('plainPassword')->getData();

            $this->userServiceManager->updateUser($userData);

            $this->addFlash('notice', 'Your changes were saved!');

            return $this->redirectToRoute('admin_edit_users', ['id' => $user->getId()]);
        }

        return $this->render('admin/pages/users/singlepage.html.twig', [
            'form' => $userProfileForm->createView(),
        ]);
    }

}