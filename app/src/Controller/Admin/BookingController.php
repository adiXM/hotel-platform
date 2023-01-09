<?php

namespace App\Controller\Admin;

use App\Entity\Booking;
use App\Form\Booking\BookingFormType;
use App\Form\DeleteFormType;
use App\Service\EntityManagerServices\BookingManagerInterface;
use App\Service\TableService;
use App\Table\BookingTableType;
use Omines\DataTablesBundle\Adapter\ArrayAdapter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BookingController extends AbstractController
{
    public function __construct(
        private readonly TableService $tableService,
        private readonly BookingManagerInterface $bookingManagerService,
    )
    {
    }

    #[Route('/admin/bookings', name: 'admin_bookings')]
    public function index(Request $request): Response
    {
        $hasAccess = $this->isGranted('ROLE_ADMIN');
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $table = $this->tableService->createTableType(BookingTableType::class)
            ->createAdapter(ArrayAdapter::class, $this->bookingManagerService->getBookingsList())->handleRequest($request);

        if ($table->isCallback()) {
            return $table->getResponse();
        }

        $booking = new Booking();

        $bookingForm = $this->createForm(BookingFormType::class, $booking);
        $bookingForm->handleRequest($request);

        if ($bookingForm->isSubmitted() && $bookingForm->isValid()) {

            /** @var Booking $bookingFormData */
            $bookingFormData = $bookingForm->getData();

            try {
                $this->bookingManagerService->updateBooking($bookingFormData);

                $this->addFlash('success', 'Your changes were saved!');

            } catch (\Exception $ex) {
                $this->addFlash('danger', $ex->getMessage());
            }

            return $this->redirectToRoute('admin_bookings');
        }

        $deleteForm = $this->createForm(DeleteFormType::class);
        $deleteForm->handleRequest($request);

        if ($deleteForm->isSubmitted() && $deleteForm->isValid()) {

            $rowId = $deleteForm->get('rowId')->getData();

            try {
                $this->bookingManagerService->removeBooking($rowId);

                $this->addFlash('success', 'Your changes were saved!');

            } catch (\Exception $ex) {
                $this->addFlash('danger', $ex->getMessage());
            }

            return $this->redirectToRoute('admin_bookings');
        }

        return $this->render('admin/pages/booking/index.html.twig', [
            'form' => $bookingForm->createView(),
            'deleteForm' => $deleteForm->createView(),
            'bookingsTable' => $table
        ]);
    }
}