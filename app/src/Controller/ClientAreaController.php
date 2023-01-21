<?php

namespace App\Controller;

use App\Entity\Customer;
use App\Form\Customer\CustomerFormType;
use App\Form\Frontend\Clientarea\ProfileFormType;
use App\Form\Frontend\RegistrationFormType;
use App\Service\EntityManagerServices\BookingManagerInterface;
use App\Service\EntityManagerServices\CustomerManagerInterface;
use App\Service\Frontend\DataManagerInterface;
use App\Service\TableService;
use App\Table\Frontend\BookingTableType;
use Omines\DataTablesBundle\Adapter\ArrayAdapter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ClientAreaController extends AbstractController
{
    public function __construct(
        private readonly DataManagerInterface $dataManager,
        private readonly CustomerManagerInterface $customerManagerService,
        private readonly TableService $tableService,
    )
    {
    }

    #[Route('/clientarea', name: 'app_clientarea')]
    public function index(Request $request): Response
    {
        $hasAccess = $this->isGranted('ROLE_USER');
        $this->denyAccessUnlessGranted('ROLE_USER');
        /** @var Customer $customer */
        $customer = $this->getUser();
        if($customer) {
            $customerFullName = $customer->getFirstName().' '.$customer->getLastName();
        }

        $bookings = $customer->getBookings();
        $totalSpent = 0;
        foreach ($bookings as $booking) {
            $totalSpent += ($booking->getPrice());
        }

        $upcomingBookings = 0;
        foreach ($bookings as $booking) {
            $checkinDate = $booking->getCheckin()->format('Y-m-d');
            $todayDate = date("Y-m-d");
            if($checkinDate > $todayDate) {
                $upcomingBookings++;
            }
        }



        return $this->render('pages/clientarea/overview/index.html.twig', [
            'rooms' => $this->dataManager->getHomepageRooms(),
            'customer_fullname' => $customerFullName,
            'total_bookings' => $bookings->count(),
            'total_spent' => $totalSpent,
            'upcoming_bookings' => $upcomingBookings
        ]);
    }

    #[Route('/clientarea/profile', name: 'app_clientarea_profile')]
    public function profile(Request $request): Response
    {
        $hasAccess = $this->isGranted('ROLE_USER');
        $this->denyAccessUnlessGranted('ROLE_USER');

        /** @var Customer $customer */
        $customer = $this->getUser();

        if($customer) {
            $customerFullName = $customer->getFirstName().' '.$customer->getLastName();
        }

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
            'customer_fullname' => $customerFullName
        ]);
    }

    #[Route('/clientarea/bookings', name: 'app_clientarea_bookings')]
    public function bookings(Request $request): Response
    {

        $hasAccess = $this->isGranted('ROLE_USER');
        $this->denyAccessUnlessGranted('ROLE_USER');

        /** @var Customer $customer */
        $customer = $this->getUser();

        if($customer) {
            $customerFullName = $customer->getFirstName().' '.$customer->getLastName();
        }

        $table = $this->tableService->createTableType(BookingTableType::class)
            ->createAdapter(ArrayAdapter::class, $this->dataManager->getBookingsByCustomer($customer))->handleRequest($request);

        if ($table->isCallback()) {
            return $table->getResponse();
        }


        return $this->render('pages/clientarea/bookings/index.html.twig', [
            'bookingsTable' => $table,
            'customer_fullname' => $customerFullName
        ]);
    }
}