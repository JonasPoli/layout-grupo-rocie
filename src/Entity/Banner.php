<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[ORM\Entity]
#[ORM\Table(name: 'banners')]
#[ORM\HasLifecycleCallbacks]
#[Vich\Uploadable]
class Banner
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $subtitle = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $text = null;

    #[Vich\UploadableField(mapping: 'rocie_banner_image', fileNameProperty: 'desktopImage')]
    private ?File $desktopImageFile = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $desktopImage = null;

    #[Vich\UploadableField(mapping: 'rocie_banner_image', fileNameProperty: 'mobileImage')]
    private ?File $mobileImageFile = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $mobileImage = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $buttonText = null;

    #[ORM\Column(length: 500, nullable: true)]
    private ?string $buttonUrl = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $secondaryButtonText = null;

    #[ORM\Column(length: 500, nullable: true)]
    private ?string $secondaryButtonUrl = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $displayPage = 'home';

    #[ORM\Column(nullable: true)]
    private ?int $sortOrder = 0;

    #[ORM\Column]
    private bool $active = true;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    public function __toString(): string { return $this->title ?? ''; }

    public function getId(): ?int { return $this->id; }
    public function getTitle(): ?string { return $this->title; }
    public function setTitle(string $title): static { $this->title = $title; return $this; }
    public function getSubtitle(): ?string { return $this->subtitle; }
    public function setSubtitle(?string $v): static { $this->subtitle = $v; return $this; }
    public function getText(): ?string { return $this->text; }
    public function setText(?string $v): static { $this->text = $v; return $this; }
    public function getDesktopImageFile(): ?File { return $this->desktopImageFile; }
    public function setDesktopImageFile(?File $file): static { $this->desktopImageFile = $file; if ($file) { $this->updatedAt = new \DateTimeImmutable(); } return $this; }
    public function getDesktopImage(): ?string { return $this->desktopImage; }
    public function setDesktopImage(?string $v): static { $this->desktopImage = $v; return $this; }
    public function getMobileImageFile(): ?File { return $this->mobileImageFile; }
    public function setMobileImageFile(?File $file): static { $this->mobileImageFile = $file; if ($file) { $this->updatedAt = new \DateTimeImmutable(); } return $this; }
    public function getMobileImage(): ?string { return $this->mobileImage; }
    public function setMobileImage(?string $v): static { $this->mobileImage = $v; return $this; }
    public function getButtonText(): ?string { return $this->buttonText; }
    public function setButtonText(?string $v): static { $this->buttonText = $v; return $this; }
    public function getButtonUrl(): ?string { return $this->buttonUrl; }
    public function setButtonUrl(?string $v): static { $this->buttonUrl = $v; return $this; }
    public function getSecondaryButtonText(): ?string { return $this->secondaryButtonText; }
    public function setSecondaryButtonText(?string $v): static { $this->secondaryButtonText = $v; return $this; }
    public function getSecondaryButtonUrl(): ?string { return $this->secondaryButtonUrl; }
    public function setSecondaryButtonUrl(?string $v): static { $this->secondaryButtonUrl = $v; return $this; }
    public function getDisplayPage(): ?string { return $this->displayPage; }
    public function setDisplayPage(?string $v): static { $this->displayPage = $v; return $this; }
    public function getSortOrder(): ?int { return $this->sortOrder; }
    public function setSortOrder(?int $v): static { $this->sortOrder = $v; return $this; }
    public function isActive(): bool { return $this->active; }
    public function setActive(bool $v): static { $this->active = $v; return $this; }
    public function getUpdatedAt(): ?\DateTimeImmutable { return $this->updatedAt; }
}
