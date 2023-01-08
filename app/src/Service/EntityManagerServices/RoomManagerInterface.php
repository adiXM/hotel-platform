<?php

namespace App\Service\EntityManagerServices;

use App\Entity\Room;

interface RoomManagerInterface
{
    public function getRoomList(): array;

    public function updateRoom(Room $room): void;

    public function removeRoom(string $roomId): void;
}