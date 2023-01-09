<?php

namespace App\Service\EntityManagerServices;

use App\Entity\Amenity;
use App\Entity\Booking;
use App\Repository\AmenityRepository;
use App\Repository\BookingRepository;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectManager;

class BookingManagerService implements BookingManagerInterface
{
    private ObjectManager $entityManager;

    public function __construct(
        private readonly ManagerRegistry $doctrine,
        private readonly BookingRepository $repository
    )
    {
        $this->entityManager = $this->doctrine->getManager();
    }

    /**
     * @throws \Exception
     */
    public function getBookingsList(): array
    {
        $bookingCollection = new ArrayCollection();
        $bookings = $this->repository->findAll();

        foreach ($bookings as $booking) {
            $bookingCollection->add([
                'id' => $booking->getId(),
                'room_type_name' => $booking->getRoom()->getRoomType()->getName(),
                'room_number' => $booking->getRoom()->getRoomNumber(),
                'checkin' => $booking->getCheckin()->format('d M Y'),
                'checkout' => $booking->getCheckout()->format('d M Y'),
                'price' => $booking->getPrice()
            ]);
        }

        return $bookingCollection->toArray();
    }

    public function updateBooking(Booking $booking): void
    {
        //dd($booking);
        $this->entityManager->persist($booking);
        $this->entityManager->flush();
    }

    /**
     * @throws \Exception
     */
    public function removeBooking(string $bookingId): void
    {
        /** @var Booking $booking */
        $booking = $this->repository->find($bookingId);
        $this->entityManager->remove($booking);
        $this->entityManager->flush();
    }
}