<?php

namespace App\Service\EntityManagerServices;

use App\Entity\Room;
use App\Repository\RoomRepository;
use App\Service\EntityManagerServices\RoomManagerInterface;
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
                'room_number' => $room->getRoomNumber(),
                'active' => $room->isActive(),
                'room_type_name' => $room->getRoomType()->getName()
            ]);
        }

        return $roomsCollection->toArray();
    }

    /**
     * @throws \Exception
     */
    public function updateRoom(Room $room): void
    {

        $this->entityManager->persist($room);
        $this->entityManager->flush();
    }

    public function removeRoom(string $roomId): void
    {
        /** @var Room $room */
        $room = $this->repository->find($roomId);
        $this->entityManager->remove($room);
        $this->entityManager->flush();
    }
}