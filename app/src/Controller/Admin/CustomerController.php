<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Form\UserProfileType;
use App\Service\CustomerManagerService;
use App\Service\TableService;
use App\Table\UserTableType;
use Omines\DataTablesBundle\Adapter\ArrayAdapter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CustomerController extends AbstractController
{
    public function __construct(
        private readonly TableService $tableService,
        private readonly CustomerManagerService $customerManagerService
    )
    {
    }

    #[Route('/admin/customers', name: 'admin_customers')]
    public function index(Request $request): Response
    {
        $hasAccess = $this->isGranted('ROLE_ADMIN');
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $table = $this->tableService->createTableType(UserTableType::class)
            ->createAdapter(ArrayAdapter::class, $this->customerManagerService->getCustomerList())
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
                $userData->setPassword($this->customerManagerService->getHashPassword($userData, $plainPassword));
            }

            $this->customerManagerService->updateUser($userData);

            $this->addFlash('notice', 'Your changes were saved!');

            return $this->redirectToRoute('admin_users');
        }

        return $this->render('admin/pages/customers/index.html.twig', [
            'form' => $userProfileForm->createView(),
            'userTable' => $table
        ]);
    }
}