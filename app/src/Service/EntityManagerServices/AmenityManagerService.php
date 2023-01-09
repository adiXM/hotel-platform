<?php

namespace App\Service\EntityManagerServices;

use App\Entity\Amenity;
use App\Repository\AmenityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectManager;

class AmenityManagerService implements AmenityManagerInterface
{
    private ObjectManager $entityManager;

    public function __construct(
        private readonly ManagerRegistry $doctrine,
        private readonly AmenityRepository $repository
    )
    {
        $this->entityManager = $this->doctrine->getManager();
    }

    public function getAmenitiesList(): array
    {
        $amentityCollection = new ArrayCollection();
        $amenities = $this->repository->findAll();

        foreach ($amenities as $amenity) {
            $amentityCollection->add([
                'id' => $amenity->getId(),
                'name' => $amenity->getName(),
                'icon_class' => $amenity->getIconClass(),
            ]);
        }

        return $amentityCollection->toArray();
    }

    public function updateAmenity(Amenity $amenity): void
    {
        $this->entityManager->persist($amenity);
        $this->entityManager->flush();
    }

    /**
     * @throws \Exception
     */
    public function removeAmenity(string $amenityId): void
    {
        /** @var Amenity $amenity */
        $amenity = $this->repository->find($amenityId);
        $roomTypesCount = $amenity->getRoomTypes()->count();
        if($roomTypesCount > 0) {
            throw new \Exception(sprintf('You cannot delete this amenity because you have %s room types associated.', $roomTypesCount));
        }
        $this->entityManager->remove($amenity);
        $this->entityManager->flush();
    }
}