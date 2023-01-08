<?php

namespace App\Service\EntityManagerServices;

use App\Entity\RoomType;

interface RoomTypeManagerInterface
{
    public function getRoomTypeList(): array;

    public function updateRoomType(RoomType $roomType): void;

    public function removeRoomType(string $roomTypeId): void;
}