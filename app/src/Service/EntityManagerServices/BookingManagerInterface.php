<?php

namespace App\Service\EntityManagerServices;

use App\Entity\Booking;

interface BookingManagerInterface
{
    public function getBookingsList(): array;

    public function updateBooking(Booking $booking): void;

    public function removeBooking(string $bookingId): void;
}