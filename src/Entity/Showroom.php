<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[ORM\Entity]
#[ORM\Table(name: 'showrooms')]
#[ORM\HasLifecycleCallbacks]
#[Vich\Uploadable]
class Showroom
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 2)]
    private ?string $state = null;

    #[ORM\Column(length: 100)]
    private ?string $city = null;

    #[ORM\Column(length: 150, nullable: true)]
    private ?string $neighborhood = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $address = null;

    #[ORM\Column(length: 20, nullable: true)]
    private ?string $number = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $complement = null;

    #[ORM\Column(length: 10, nullable: true)]
    private ?string $zipcode = null;

    #[ORM\Column(length: 30, nullable: true)]
    private ?string $phone = null;

    #[ORM\Column(length: 30, nullable: true)]
    private ?string $whatsapp = null;

    #[ORM\Column(length: 150, nullable: true)]
    private ?string $email = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $openingHours = null;

    #[ORM\Column(length: 500, nullable: true)]
    private ?string $googleMapsUrl = null;

    #[Vich\UploadableField(mapping: 'rocie_showroom_image', fileNameProperty: 'mainImage')]
    private ?File $mainImageFile = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $mainImage = null;

    #[ORM\Column(nullable: true)]
    private ?int $sortOrder = 0;

    #[ORM\Column]
    private bool $active = true;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    public function __toString(): string { return $this->name . ' - ' . $this->city . '/' . $this->state; }

    public function getId(): ?int { return $this->id; }
    public function getName(): ?string { return $this->name; }
    public function setName(string $name): static { $this->name = $name; return $this; }
    public function getState(): ?string { return $this->state; }
    public function setState(string $state): static { $this->state = $state; return $this; }
    public function getCity(): ?string { return $this->city; }
    public function setCity(string $city): static { $this->city = $city; return $this; }
    public function getNeighborhood(): ?string { return $this->neighborhood; }
    public function setNeighborhood(?string $v): static { $this->neighborhood = $v; return $this; }
    public function getAddress(): ?string { return $this->address; }
    public function setAddress(?string $v): static { $this->address = $v; return $this; }
    public function getNumber(): ?string { return $this->number; }
    public function setNumber(?string $v): static { $this->number = $v; return $this; }
    public function getComplement(): ?string { return $this->complement; }
    public function setComplement(?string $v): static { $this->complement = $v; return $this; }
    public function getZipcode(): ?string { return $this->zipcode; }
    public function setZipcode(?string $v): static { $this->zipcode = $v; return $this; }
    public function getPhone(): ?string { return $this->phone; }
    public function setPhone(?string $v): static { $this->phone = $v; return $this; }
    public function getWhatsapp(): ?string { return $this->whatsapp; }
    public function setWhatsapp(?string $v): static { $this->whatsapp = $v; return $this; }
    public function getEmail(): ?string { return $this->email; }
    public function setEmail(?string $v): static { $this->email = $v; return $this; }
    public function getOpeningHours(): ?string { return $this->openingHours; }
    public function setOpeningHours(?string $v): static { $this->openingHours = $v; return $this; }
    public function getGoogleMapsUrl(): ?string { return $this->googleMapsUrl; }
    public function setGoogleMapsUrl(?string $v): static { $this->googleMapsUrl = $v; return $this; }
    public function getMainImageFile(): ?File { return $this->mainImageFile; }
    public function setMainImageFile(?File $file): static { $this->mainImageFile = $file; if ($file) { $this->updatedAt = new \DateTimeImmutable(); } return $this; }
    public function getMainImage(): ?string { return $this->mainImage; }
    public function setMainImage(?string $v): static { $this->mainImage = $v; return $this; }
    public function getSortOrder(): ?int { return $this->sortOrder; }
    public function setSortOrder(?int $v): static { $this->sortOrder = $v; return $this; }
    public function isActive(): bool { return $this->active; }
    public function setActive(bool $v): static { $this->active = $v; return $this; }
    public function getUpdatedAt(): ?\DateTimeImmutable { return $this->updatedAt; }
}
