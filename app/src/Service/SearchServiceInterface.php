<?php

namespace App\Service;

use App\Entity\Room;
use App\Entity\RoomType;

interface SearchServiceInterface
{
    public function searchRoomTypes(array $parameters, array $selectedAmenities): array;

    public function checkRoomType(RoomType $roomType, string $checkin, string $checkout, array $selectedAmenities = [], Room &$roomEntity = null): bool;

}