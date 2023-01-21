<?php

namespace App\Controller\Admin;

use App\Entity\Amenity;
use App\Entity\Customer;
use App\Entity\User;
use App\Form\AmenityFormType;
use App\Form\Customer\CustomerFormType;
use App\Form\DeleteFormType;
use App\Form\UserProfileType;
use App\Service\EntityManagerServices\CustomerManagerService;
use App\Service\MailerServiceInterface;
use App\Service\TableService;
use App\Table\CustomerTableType;
use Omines\DataTablesBundle\Adapter\ArrayAdapter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CustomerController extends AbstractController
{
    public function __construct(
        private readonly TableService $tableService,
        private readonly CustomerManagerService $customerManagerService,
        private readonly MailerServiceInterface $mailerService
    )
    {
    }

    #[Route('/admin/customers', name: 'admin_customers')]
    public function index(Request $request): Response
    {
        $hasAccess = $this->isGranted('ROLE_ADMIN');
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $table = $this->tableService->createTableType(CustomerTableType::class)
            ->createAdapter(ArrayAdapter::class, $this->customerManagerService->getCustomerList())
            ->handleRequest($request);

        if ($table->isCallback()) {
            return $table->getResponse();
        }

        $customer = new Customer();

        $customerForm = $this->createForm(CustomerFormType::class, $customer);

        $customerForm->handleRequest($request);

        if ($customerForm->isSubmitted() && $customerForm->isValid()) {

            /** @var Customer $customerData */
            $customerData = $customerForm->getData();

            $plainPassword = $customerForm->get('plainPassword')->getData();

            if($plainPassword !== null) {
                $customerData->setPassword($this->customerManagerService->getHashPassword($customerData, $plainPassword));
            }

            $this->customerManagerService->updateUser($customerData);

            $this->addFlash('notice', 'Your changes were saved!');

            return $this->redirectToRoute('admin_customers');
        }

        $deleteForm = $this->createForm(DeleteFormType::class);
        $deleteForm->handleRequest($request);

        if ($deleteForm->isSubmitted() && $deleteForm->isValid()) {

            $rowId = $deleteForm->get('rowId')->getData();

            try {
                $this->customerManagerService->removeCustomer($rowId);

                $this->addFlash('success', 'Your changes were saved!');

            } catch (\Exception $ex) {
                $this->addFlash('danger', $ex->getMessage());
            }

            return $this->redirectToRoute('admin_customers');
        }

        return $this->render('admin/pages/customers/index.html.twig', [
            'deleteForm' => $deleteForm->createView(),
            'form' => $customerForm->createView(),
            'userTable' => $table
        ]);
    }

    #[Route('/admin/customer/{id}', name: 'admin_edit_customer')]
    public function edit(Customer $customer, Request $request)
    {
        $hasAccess = $this->isGranted('ROLE_ADMIN');
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $customerEditForm = $this->createForm(CustomerFormType::class, $customer);

        $customerEditForm->handleRequest($request);

        if ($customerEditForm->isSubmitted() && $customerEditForm->isValid()) {

            /** @var Customer $customerData */
            $customerData = $customerEditForm->getData();

            try {
                $this->customerManagerService->updateUser($customerData);

                $this->addFlash('notice', 'Your changes were saved!');

            } catch (\Exception $ex) {
                $this->addFlash('danger', $ex->getMessage());
            }

            return $this->redirectToRoute('admin_edit_customer', ['id' => $customerData->getId()]);
        }

        return $this->render('admin/pages/customers/singlepage.html.twig', [
            'form' => $customerEditForm->createView(),
        ]);
    }
}