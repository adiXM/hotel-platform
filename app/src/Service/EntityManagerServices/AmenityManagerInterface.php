<?php

namespace App\Service\EntityManagerServices;

use App\Entity\Amenity;

interface AmenityManagerInterface
{
    public function getAmenitiesList(): array;

    public function updateAmenity(Amenity $amenity): void;

    public function removeAmenity(string $amenityId): void;
}