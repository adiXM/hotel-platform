<?php

namespace App\Service\Frontend;

use App\Entity\Amenity;
use App\Entity\Customer;
use App\Repository\AmenityRepository;
use App\Service\EntityManagerServices\BookingManagerInterface;
use App\Service\EntityManagerServices\RoomTypeManagerInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class DataManagerService implements DataManagerInterface
{
    public function __construct(
        private RoomTypeManagerInterface $roomTypeManagerService,
        private AmenityRepository $amenityRepository,
        private BookingManagerInterface $bookingManager
    )
    {
    }

    public function getHomepageRooms(): array
    {
        return $this->roomTypeManagerService->getRoomTypeList();
    }


    public function getAllAmenities(?string $amenityIds): array
    {
        $selectedAmenities = new ArrayCollection();
        $res = [];
        if($amenityIds !== null) {
            $res = $this->amenityRepository->findBy(['id' => explode(',',$amenityIds)]);
        }

        /** @var Amenity $item */
        foreach ($res as $item) {
            $selectedAmenities->add($item);
        }
        return ['amenities' => $selectedAmenities];
    }

    public function getBookingsByCustomer(Customer $customer):array
    {
        $bookingCollection = new ArrayCollection();
        $bookings = $customer->getBookings()->toArray();

        foreach ($bookings as $booking) {
            $bookingCollection->add([
                'id' => $booking->getId(),
                'room_type_name' => $booking->getRoom()->getRoomType()->getName(),
                'checkin' => $booking->getCheckin()->format('d M Y'),
                'checkout' => $booking->getCheckout()->format('d M Y'),
                'guests' => (string)($booking->getAdults() + $booking->getChilds()),
                'price' => (string)$booking->getPrice()
            ]);
        }
        //dd($bookingCollection);

        return $bookingCollection->toArray();
    }

    public function getCurrentBookingData(SessionInterface $session): ?array
    {
        return $session->get('booking');
    }

    public function clearBookingData(SessionInterface $session): mixed
    {
        return $session->remove('booking');
    }

    public function setBookingData(SessionInterface $session, array $data): void
    {
        $session->set('booking', $data);
    }
}