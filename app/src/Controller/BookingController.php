<?php

namespace App\Controller;

use App\Entity\Customer;
use App\Entity\Media;
use App\Form\Booking\BookingCustomerFormType;
use App\Form\Frontend\Booking\BookingFormType;
use App\Form\Frontend\Search\RoomTypeSearchType;
use App\Service\BookingHandlerServiceInterface;
use App\Service\EntityManagerServices\RoomTypeManagerInterface;
use App\Service\Frontend\DataManagerInterface;
use App\Service\HelperService;
use App\Service\SearchServiceInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class BookingController  extends AbstractController
{
    public function __construct(
        private readonly DataManagerInterface $dataManager,
        private readonly SearchServiceInterface $searchService,
        private readonly RoomTypeManagerInterface $roomTypeManager,
        private readonly HelperService $helperService,
        private readonly BookingHandlerServiceInterface $bookingHandlerService
    )
    {
    }

    #[Route('/book', name: 'app_book_room')]
    public function index(Request $request, SessionInterface $session): Response
    {
        $bookingData = $this->dataManager->getCurrentBookingData($session);

        if($bookingData === null) {
            return $this->redirectToRoute('app_homepage');
        }

        $bookingFormAction = $this->createForm(BookingFormType::class);
        $bookingFormAction->handleRequest($request);

        if ($bookingFormAction->isSubmitted() && $bookingFormAction->isValid()) {
            $booking = $this->dataManager->getCurrentBookingData($session);
            /** @var Customer $customer */
            $customer = $this->getUser();
            if($customer === null) {
                return $this->redirectToRoute('app_homepage');
            }
            $booking['notes'] = $bookingFormAction->get('notes')->getData() ?? '';
            try {
                $this->bookingHandlerService->handle($booking, $customer);
                $this->dataManager->clearBookingData($session);
                return $this->redirectToRoute('app_confirm_booking');
            } catch (\Exception $ex) {
                $this->addFlash('frontend_error', 'Someting went wrong with the booking. Please contact us!'. $ex->getMessage());
                return $this->redirectToRoute('app_homepage');
            }

        }

        $form = $this->createForm(RoomTypeSearchType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $roomTypeId = $form->get('roomTypeId')->getData();
            $this->dataManager->setBookingData($session, array_merge(['roomTypeId' => $roomTypeId], $bookingData));
            $bookingData = $this->dataManager->getCurrentBookingData($session);
        }

        $roomTypeId = $bookingData['roomTypeId'];
        $roomType = $this->roomTypeManager->getRoomType($roomTypeId);

        $canBeBooked = $this->searchService->checkRoomType(
            $this->roomTypeManager->getRoomType($roomTypeId),
            $bookingData['checkin'], $bookingData['checkout'],
            []
        );

        if((int)$bookingData['adults'] > $roomType->getAdults() && (int)$bookingData['childs'] > $roomType->getChilds()) {
            $canBeBooked =  false;
        }

        if($canBeBooked === false) {
            $this->addFlash('notice', 'This room cannot be booked!');
            throw new \Exception('You cannot book this room');
        }

        $customer = $this->getUser();

        $bookingCustomerData = $this->createForm(BookingCustomerFormType::class, $customer);

        $media = $roomType->getMedia()->getValues();
        /** @var Media $mainImage */
        $mainImage = $media[array_rand($media)];

        $checkinWeekday = $this->helperService->transformDates('d-m-Y', $bookingData['checkin'], 'D');
        $checkoutWeekday = $this->helperService->transformDates('d-m-Y', $bookingData['checkout'], 'D');

        $checkin = $checkinWeekday.', '.$this->helperService->transformDates('d-m-Y', $bookingData['checkin'], 'M d Y');
        $checkout = $checkoutWeekday.', '.$this->helperService->transformDates('d-m-Y', $bookingData['checkout'], 'M d Y');

        return $this->render('pages/booking/index.html.twig', [
            'bookingCustomerData' => $bookingCustomerData->createView(),
            'roomType' => $roomType,
            'main_image_path' => $this->getParameter('public_media_directory').'/'.$mainImage->getFileName(),
            'checkin' => $checkin,
            'checkout' => $checkout,
            'adults' => $bookingData['adults'],
            'childs' => $bookingData['childs'],
            'bookingFormAction' => $bookingFormAction->createView()
        ]);

    }
}