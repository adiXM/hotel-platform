<?php

namespace App\Service\Frontend;

use App\Service\EntityManagerServices\RoomTypeManagerInterface;

class DataManagerService implements DataManagerInterface
{
    public function __construct(private RoomTypeManagerInterface $roomTypeManagerService)
    {
    }

    public function getHomepageRooms(): array
    {
        return $this->roomTypeManagerService->getRoomTypeList();
    }
}