<?php

namespace App\Service;

use App\Entity\Room;
use App\Repository\RoomRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectManager;

class RoomManagerService implements RoomManagerInterface
{
    private ObjectManager $entityManager;

    public function __construct(
        private readonly ManagerRegistry $doctrine,
        private readonly RoomRepository $repository
    )
    {
        $this->entityManager = $this->doctrine->getManager();
    }

    public function getRoomList(): array
    {
        $roomsCollection = new ArrayCollection();
        $rooms = $this->repository->findAll();

        foreach ($rooms as $room) {
            $roomsCollection->add([
                'id' => $room->getId(),
                'price' => $room->getPrice(),
                'room_number' => $room->getRoomNumber(),
                'active' => $room->isActive(),
                'room_type_name' => $room->getRoomType()->getName()
            ]);
        }

        return $roomsCollection->toArray();
    }

    public function updateRoom(Room $room): void
    {
        $this->entityManager->persist($room);
        $this->entityManager->flush();
    }
}