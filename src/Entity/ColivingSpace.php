<?php

namespace App\Entity;

use App\Repository\ColivingSpaceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ColivingSpaceRepository::class)]
#[ORM\Table(name: 'coliving_space')]
class ColivingSpace
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $titleColivingSpace = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $descriptionColivingSpace = null;

    #[ORM\Column(length: 50)]
    private ?string $housingType = null;

    #[ORM\Column]
    private ?int $roomCount = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 6, scale: 2)]
    private ?string $totalAreaM2 = null;

    #[ORM\Column]
    private ?int $capacityMax = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\Column(options: ['default' => true])]
    private ?bool $isActive = true;

    #[ORM\OneToMany(targetEntity: PrivateSpace::class, mappedBy: 'colivingSpace')]
    private Collection $privateSpaces;

    #[ORM\ManyToOne(inversedBy: 'colivingSpaces')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Address $address = null;

    #[ORM\ManyToOne(inversedBy: 'colivingSpaces')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $owner = null;

    #[ORM\OneToMany(targetEntity: Photo::class, mappedBy: 'colivingSpace', orphanRemoval: true)]
    private Collection $photos;

    #[ORM\OneToMany(targetEntity: VerificationSpace::class, mappedBy: 'colivingSpace', orphanRemoval: true)]
    private Collection $verificationSpaces;

    #[ORM\ManyToMany(targetEntity: Amenity::class, mappedBy: 'colivingSpaces')]
    private Collection $amenities;

    public function __construct()
    {
        $this->privateSpaces = new ArrayCollection();
        $this->photos = new ArrayCollection();
        $this->verificationSpaces = new ArrayCollection();
        $this->amenities = new ArrayCollection();
    }

    public function getId(): ?int { return $this->id; }

    public function getTitleColivingSpace(): ?string { return $this->titleColivingSpace; }
    public function setTitleColivingSpace(string $title): static {
        $this->titleColivingSpace = $title;
        return $this;
    }

    public function getDescriptionColivingSpace(): ?string { return $this->descriptionColivingSpace; }
    public function setDescriptionColivingSpace(string $description): static {
        $this->descriptionColivingSpace = $description;
        return $this;
    }

    public function getHousingType(): ?string { return $this->housingType; }
    public function setHousingType(string $type): static {
        $this->housingType = $type;
        return $this;
    }

    public function getRoomCount(): ?int { return $this->roomCount; }
    public function setRoomCount(int $count): static {
        $this->roomCount = $count;
        return $this;
    }

    public function getTotalAreaM2(): ?string { return $this->totalAreaM2; }
    public function setTotalAreaM2(string $area): static {
        $this->totalAreaM2 = $area;
        return $this;
    }

    public function getCapacityMax(): ?int { return $this->capacityMax; }
    public function setCapacityMax(int $capacity): static {
        $this->capacityMax = $capacity;
        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable { return $this->createdAt; }
    public function setCreatedAt(\DateTimeImmutable $createdAt): static {
        $this->createdAt = $createdAt;
        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable { return $this->updatedAt; }
    public function setUpdatedAt(?\DateTimeImmutable $updatedAt): static {
        $this->updatedAt = $updatedAt;
        return $this;
    }

    public function getIsActive(): ?bool { return $this->isActive; }
    public function setIsActive(bool $isActive): static {
        $this->isActive = $isActive;
        return $this;
    }

    public function getAddress(): ?Address { return $this->address; }
    public function setAddress(?Address $address): static {
        $this->address = $address;
        return $this;
    }

    public function getOwner(): ?User { return $this->owner; }
    public function setOwner(?User $owner): static {
        $this->owner = $owner;
        return $this;
    }

    /** @return Collection<int, PrivateSpace> */
    public function getPrivateSpaces(): Collection { return $this->privateSpaces; }

    /** @return Collection<int, Photo> */
    public function getPhotos(): Collection { return $this->photos; }

    /** @return Collection<int, VerificationSpace> */
    public function getVerificationSpaces(): Collection { return $this->verificationSpaces; }

    /** @return Collection<int, Amenity> */
    public function getAmenities(): Collection { return $this->amenities; }

    public function addAmenity(Amenity $amenity): static {
        if (!$this->amenities->contains($amenity)) {
            $this->amenities->add($amenity);
            $amenity->addColivingSpace($this); // synchronisation bidirectionnelle
        }
        return $this;
    }

    public function removeAmenity(Amenity $amenity): static {
        if ($this->amenities->removeElement($amenity)) {
            $amenity->removeColivingSpace($this); // synchronisation bidirectionnelle
        }
        return $this;
    }
}
