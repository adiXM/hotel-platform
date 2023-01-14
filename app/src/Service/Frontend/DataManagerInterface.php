<?php

namespace App\Service\Frontend;

use Doctrine\Common\Collections\ArrayCollection;

interface DataManagerInterface
{
    public function getHomepageRooms(): array;

    public function getAllAmenities(string $amenityIds): array;
}