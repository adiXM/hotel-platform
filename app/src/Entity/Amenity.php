<?php

namespace App\Entity;

use App\Repository\AmenityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AmenityRepository::class)]
class Amenity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $icon_class = null;

    #[ORM\ManyToMany(targetEntity: RoomType::class, inversedBy: 'amenities')]
    private Collection $roomTypes;

    public function __construct()
    {
        $this->roomTypes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getIconClass(): ?string
    {
        return $this->icon_class;
    }

    public function setIconClass(string $icon_class): self
    {
        $this->icon_class = $icon_class;

        return $this;
    }

    /**
     * @return Collection<int, RoomType>
     */
    public function getRoomTypes(): Collection
    {
        return $this->roomTypes;
    }

    public function addRoomType(RoomType $roomType): self
    {
        if (!$this->roomTypes->contains($roomType)) {
            $this->roomTypes->add($roomType);
        }

        return $this;
    }

    public function removeRoomType(RoomType $roomType): self
    {
        $this->roomTypes->removeElement($roomType);

        return $this;
    }
}
