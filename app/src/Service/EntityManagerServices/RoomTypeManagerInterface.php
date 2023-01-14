<?php

namespace App\Service\EntityManagerServices;

use App\Entity\Media;
use App\Entity\RoomType;

interface RoomTypeManagerInterface
{
    public function getRoomTypeList(): array;

    public function updateRoomType(RoomType $roomType, array $mediaNames): void;

    public function removeRoomType(string $roomTypeId): void;

    public function addMediaToRoomType(RoomType $roomType, array $mediaRoomTypeList): void;

    public function getRoomType(string $roomTypeId): RoomType;

}