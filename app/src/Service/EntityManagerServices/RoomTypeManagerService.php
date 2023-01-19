<?php

namespace App\Service\EntityManagerServices;

use App\Entity\Media;
use App\Entity\RoomType;
use App\Repository\RoomTypeRepository;
use Symfony\Component\HttpFoundation\File\UploadedFile;
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
            $amenityList = $roomType->getAmenities()->toArray();
            $amenityValues = [];
            foreach ($amenityList as $amenity) {
                $amenityValues [] = $amenity->getName();
            }

            $media = $roomType->getMedia()->getValues();
            /** @var Media $mainImage */
            $mainImage = $media[array_rand($media)];

            $roomTypesCollection->add([
                'id' => $roomType->getId(),
                'name' => $roomType->getName(),
                'price' => $roomType->getPrice(),
                'description' => $roomType->getDescription(),
                'amenities' => $amenityValues,
                'main_image' => $mainImage->getFileName()
            ]);
        }

        return $roomTypesCollection->toArray();
    }

    public function getRoomType(string $roomTypeId): RoomType
    {
        return $this->repository->find($roomTypeId);
    }

    public function addMediaToRoomType(RoomType $roomType, array $mediaNames): void
    {
        foreach ($mediaNames as $mediaFile) {
            $media = new Media();
            $media->setFileName($mediaFile);
            $roomType->addMedia($media);
            $media->setRoomType($roomType);
        }
    }

    public function updateRoomType(RoomType $roomType, array $mediaNames): void
    {
        $this->addMediaToRoomType($roomType, $mediaNames);

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