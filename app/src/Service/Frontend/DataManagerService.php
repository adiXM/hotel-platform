<?php

namespace App\Service\Frontend;

use App\Entity\Amenity;
use App\Repository\AmenityRepository;
use App\Service\EntityManagerServices\RoomTypeManagerInterface;
use Doctrine\Common\Collections\ArrayCollection;

class DataManagerService implements DataManagerInterface
{
    public function __construct(
        private RoomTypeManagerInterface $roomTypeManagerService,
        private AmenityRepository $amenityRepository
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
}