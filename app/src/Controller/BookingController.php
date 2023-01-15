<?php

namespace App\Controller;

use App\Entity\Customer;
use App\Entity\Media;
use App\Form\Booking\BookingCustomerFormType;
use App\Form\Frontend\Clientarea\ProfileFormType;
use App\Form\Frontend\RegistrationFormType;
use App\Service\EntityManagerServices\CustomerManagerInterface;
use App\Service\EntityManagerServices\RoomTypeManagerInterface;
use App\Service\Frontend\DataManagerInterface;
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
        private readonly CustomerManagerInterface $customerManagerService,
        private readonly SearchServiceInterface $searchService,
    private readonly RoomTypeManagerInterface $roomTypeManager)
    {
    }


    #[Route('/book/{id}', name: 'app_book_room')]
    public function index(int $id, Request $request, SessionInterface $session): Response
    {

        $checkin = $session->get('checkin');
        $checkout = $session->get('checkout');

        if($checkin === null || $checkout === null) {
            return $this->redirectToRoute('app_homepage');
        }
        $canBeBooked = $this->searchService->checkRoomType(
            $this->roomTypeManager->getRoomType($id),
            $checkin, $checkout,
            []
        );

        if($canBeBooked === false) {
            $this->addFlash('notice', 'Your changes were saved!');
            throw new \Exception('You cannot book this room');
        }

        $session->set('booking', [
            'checkin' => $checkin,
            'checkout' => $checkout,
            'roomTypeId' => $id
        ]);

        $customer = $this->getUser();
        if($customer === null) {
            $customer = new Customer();
        }

        $bookingCustomerData = $this->createForm(BookingCustomerFormType::class, $customer);

        $roomType = $this->roomTypeManager->getRoomType($id);


        $media = $roomType->getMedia()->getValues();
        /** @var Media $mainImage */
        $mainImage = $media[array_rand($media)];

        return $this->render('pages/booking/index.html.twig', [
            'bookingCustomerData' => $bookingCustomerData->createView(),
            'roomType' => $roomType,
            'main_image_path' => $this->getParameter('public_media_directory').'/'.$mainImage->getFileName(),
            'checkin' => $checkin,
            'checkout' => $checkout
        ]);
    }
}