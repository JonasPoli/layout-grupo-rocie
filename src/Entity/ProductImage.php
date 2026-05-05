<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[ORM\Entity]
#[ORM\Table(name: 'product_images')]
#[ORM\HasLifecycleCallbacks]
#[Vich\Uploadable]
class ProductImage
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Product::class, inversedBy: 'images')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private ?Product $product = null;

    #[Vich\UploadableField(mapping: 'rocie_product_image', fileNameProperty: 'image')]
    private ?File $imageFile = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $image = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $altText = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $caption = null;

    #[ORM\Column]
    private bool $isMain = false;

    #[ORM\Column(nullable: true)]
    private ?int $sortOrder = 0;

    #[ORM\Column]
    private bool $active = true;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    public function getId(): ?int { return $this->id; }
    public function getProduct(): ?Product { return $this->product; }
    public function setProduct(?Product $product): static { $this->product = $product; return $this; }
    public function getImageFile(): ?File { return $this->imageFile; }
    public function setImageFile(?File $file): static { $this->imageFile = $file; if ($file) { $this->updatedAt = new \DateTimeImmutable(); } return $this; }
    public function getImage(): ?string { return $this->image; }
    public function setImage(?string $v): static { $this->image = $v; return $this; }
    public function getAltText(): ?string { return $this->altText; }
    public function setAltText(?string $v): static { $this->altText = $v; return $this; }
    public function getCaption(): ?string { return $this->caption; }
    public function setCaption(?string $v): static { $this->caption = $v; return $this; }
    public function isMain(): bool { return $this->isMain; }
    public function setIsMain(bool $v): static { $this->isMain = $v; return $this; }
    public function getSortOrder(): ?int { return $this->sortOrder; }
    public function setSortOrder(?int $v): static { $this->sortOrder = $v; return $this; }
    public function isActive(): bool { return $this->active; }
    public function setActive(bool $v): static { $this->active = $v; return $this; }
    public function getUpdatedAt(): ?\DateTimeImmutable { return $this->updatedAt; }
}
