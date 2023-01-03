<?php

namespace App\Service;

use App\Entity\Room;

interface RoomManagerInterface
{
    public function getRoomList(): array;

    public function updateRoom(Room $room): void;
}