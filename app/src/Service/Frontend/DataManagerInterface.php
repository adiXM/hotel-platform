<?php

namespace App\Service\Frontend;

use Symfony\Component\HttpFoundation\Session\SessionInterface;

interface DataManagerInterface
{
    public function getHomepageRooms(): array;

    public function getAllAmenities(string $amenityIds): array;

    public function getCurrentBookingData(SessionInterface $session): ?array;

    public function setBookingData(SessionInterface $session, array $data): void;

    public function clearBookingData(SessionInterface $session): mixed;
}