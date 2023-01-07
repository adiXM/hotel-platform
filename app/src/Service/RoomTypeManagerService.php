<?php

namespace App\Service;

use App\Entity\RoomType;
use App\Repository\RoomTypeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectManager;

class RoomTypeManagerService implements RoomTypeManagerInterface
{
    private ObjectManager $entityManager;

    public function __construct(
        private readonly ManagerRegistry $doctrine,
        private readonly RoomTypeRepository $repository
    )
    {
        $this->entityManager = $this->doctrine->getManager();
    }

    public function getRoomTypeList(): array
    {
        $roomTypesCollection = new ArrayCollection();
        $roomTypes = $this->repository->findAll();

        foreach ($roomTypes as $roomType) {
            $roomTypesCollection->add([
                'id' => $roomType->getId(),
                'name' => $roomType->getName(),
                'description' => $roomType->getDescription(),
                //'amenities' => $roomType->getAmenities()
            ]);
        }

        return $roomTypesCollection->toArray();
    }

    public function updateRoomType(RoomType $roomType): void
    {
        $this->entityManager->persist($roomType);
        $this->entityManager->flush();
    }

    /**
     * @throws \Exception
     */
    public function removeRoomType(string $roomTypeId): void
    {
        /** @var RoomType $roomType */
        $roomType = $this->repository->find($roomTypeId);
        $roomsCount = $roomType->getRooms()->count();
        if($roomsCount > 0) {
            throw new \Exception(sprintf('You cannot delete this room type because you have %s rooms associated.', $roomsCount));
        }
        $this->entityManager->remove($roomType);
        $this->entityManager->flush();
    }
}