<?php

namespace App\Service;

use App\Entity\Booking;
use App\Entity\Customer;
use App\Entity\Room;
use App\Service\EntityManagerServices\BookingManagerService;
use App\Service\EntityManagerServices\RoomTypeManagerInterface;

class BookingHandlerService implements BookingHandlerServiceInterface
{
    public function __construct(
        private readonly BookingManagerService $bookingManagerService,
        private readonly RoomTypeManagerInterface $roomTypeManager,
        private readonly SearchServiceInterface $searchService,
    )
    {
    }

    /**
     * @throws \Exception
     */
    public function handle(array $bookingData, Customer $customer): void
    {
        $roomType = $this->roomTypeManager->getRoomType($bookingData['roomTypeId']);
        $room = new Room();

        $canBeBooked = $this->searchService->checkRoomType($roomType, $bookingData['checkin'], $bookingData['checkout'], [], $room);

        $checkin = new \DateTime($bookingData['checkin']);
        $checkout = new \DateTime($bookingData['checkout']);

        if($canBeBooked === true) {
            $booking = new Booking();
            $booking->setPrice($roomType->getPrice());
            $booking->setCustomer($customer);
            $booking->setRoom($room);
            $booking->setCheckin($checkin);
            $booking->setCheckout($checkout);
            $booking->setAdults((int)$bookingData['adults']);
            $booking->setChilds((int)$bookingData['childs']);
            $booking->setNote((string)$bookingData['notes']);
            $this->bookingManagerService->updateBooking($booking);
        } else {
            throw new \Exception('This room cannot be booked.');
        }
    }
}