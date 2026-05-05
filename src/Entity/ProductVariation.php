<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[ORM\Entity]
#[ORM\Table(name: 'product_variations')]
#[ORM\HasLifecycleCallbacks]
#[Vich\Uploadable]
class ProductVariation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Product::class, inversedBy: 'variations')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private ?Product $product = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $sku = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $ean = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $color = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $size = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $model = null;

    #[Vich\UploadableField(mapping: 'rocie_product_image', fileNameProperty: 'image')]
    private ?File $imageFile = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $image = null;

    #[ORM\Column]
    private bool $active = true;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    public function getId(): ?int { return $this->id; }
    public function getProduct(): ?Product { return $this->product; }
    public function setProduct(?Product $product): static { $this->product = $product; return $this; }
    public function getName(): ?string { return $this->name; }
    public function setName(string $name): static { $this->name = $name; return $this; }
    public function getSku(): ?string { return $this->sku; }
    public function setSku(?string $v): static { $this->sku = $v; return $this; }
    public function getEan(): ?string { return $this->ean; }
    public function setEan(?string $v): static { $this->ean = $v; return $this; }
    public function getColor(): ?string { return $this->color; }
    public function setColor(?string $v): static { $this->color = $v; return $this; }
    public function getSize(): ?string { return $this->size; }
    public function setSize(?string $v): static { $this->size = $v; return $this; }
    public function getModel(): ?string { return $this->model; }
    public function setModel(?string $v): static { $this->model = $v; return $this; }
    public function getImageFile(): ?File { return $this->imageFile; }
    public function setImageFile(?File $file): static { $this->imageFile = $file; if ($file) { $this->updatedAt = new \DateTimeImmutable(); } return $this; }
    public function getImage(): ?string { return $this->image; }
    public function setImage(?string $v): static { $this->image = $v; return $this; }
    public function isActive(): bool { return $this->active; }
    public function setActive(bool $v): static { $this->active = $v; return $this; }
    public function getUpdatedAt(): ?\DateTimeImmutable { return $this->updatedAt; }
}
