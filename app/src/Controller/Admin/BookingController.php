<?php

namespace App\Controller\Admin;

use App\Entity\Booking;
use App\Entity\Room;
use App\Form\Booking\BookingFormType;
use App\Form\DeleteFormType;
use App\Form\RoomEditType;
use App\Service\EntityManagerServices\BookingManagerInterface;
use App\Service\HelperService;
use App\Service\SearchServiceInterface;
use App\Service\TableService;
use App\Table\BookingTableType;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
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
        private readonly HelperService $helperService,
        private readonly SearchServiceInterface $searchService,
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

            $roomType = $bookingFormData->getRoom()->getRoomType();
            $checkin = $bookingFormData->getCheckin()->format('d-m-Y');
            $checkout = $bookingFormData->getCheckout()->format('d-m-Y');
            $adults = $bookingFormData->getAdults();
            $childs = $bookingFormData->getChilds();

            $canBeBooked = $this->searchService->checkRoomType(
                $roomType,
                $checkin, $checkout,
                []
            );

            if((int)$adults > $roomType->getAdults() || (int)$childs > $roomType->getChilds()) {
                $this->addFlash('warning', 'The number of adults or children exceeds the capacity of the room');
                $canBeBooked =  false;
            }

            if($canBeBooked === false) {
                $this->addFlash('warning', 'This room cannot be booked!');
                return $this->redirectToRoute('admin_bookings');
            }

            if($bookingFormData->getPrice() === null) {
                $noNights = $this->helperService->getNumberOfNights($checkin, $checkout);
                $totalPrice = (float) ((int)$noNights * $roomType->getPrice());
                $bookingFormData->setPrice($totalPrice);
            }
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

    #[Route('/admin/booking/{id}', name: 'admin_edit_booking')]
    public function edit(Booking $booking, Request $request)
    {
        $hasAccess = $this->isGranted('ROLE_ADMIN');
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $bookingEditForm = $this->createForm(BookingFormType::class, $booking);

        $bookingEditForm->handleRequest($request);

        if ($bookingEditForm->isSubmitted() && $bookingEditForm->isValid()) {

            /** @var Booking $bookingData */
            $bookingData = $bookingEditForm->getData();

            try {
                $this->bookingManagerService->updateBooking($bookingData);

                $this->addFlash('notice', 'Your changes were saved!');

            } catch (\Exception $ex) {
                $this->addFlash('danger', $ex->getMessage());
            }

            return $this->redirectToRoute('admin_edit_booking', ['id' => $bookingData->getId()]);
        }

        return $this->render('admin/pages/booking/singlepage.html.twig', [
            'form' => $bookingEditForm->createView(),
        ]);
    }
}