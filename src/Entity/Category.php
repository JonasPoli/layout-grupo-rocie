<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[ORM\Entity]
#[ORM\Table(name: 'categories')]
#[ORM\HasLifecycleCallbacks]
#[Vich\Uploadable]
class Category
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255, unique: true)]
    private ?string $slug = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $shortDescription = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $fullDescription = null;

    #[Vich\UploadableField(mapping: 'rocie_category_image', fileNameProperty: 'image')]
    private ?File $imageFile = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $image = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $icon = null;

    #[ORM\ManyToOne(targetEntity: self::class, inversedBy: 'children')]
    private ?self $parent = null;

    #[ORM\OneToMany(mappedBy: 'parent', targetEntity: self::class)]
    private Collection $children;

    #[ORM\Column(nullable: true)]
    private ?int $sortOrder = 0;

    #[ORM\Column]
    private bool $showOnHome = false;

    #[ORM\Column]
    private bool $active = true;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $seoTitle = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $seoDescription = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\OneToMany(mappedBy: 'mainCategory', targetEntity: Product::class)]
    private Collection $products;

    public function __construct()
    {
        $this->children = new ArrayCollection();
        $this->products = new ArrayCollection();
    }

    public function __toString(): string { return $this->name ?? ''; }

    public function getId(): ?int { return $this->id; }
    public function getName(): ?string { return $this->name; }
    public function setName(string $name): static { $this->name = $name; return $this; }
    public function getSlug(): ?string { return $this->slug; }
    public function setSlug(string $slug): static { $this->slug = $slug; return $this; }
    public function getShortDescription(): ?string { return $this->shortDescription; }
    public function setShortDescription(?string $v): static { $this->shortDescription = $v; return $this; }
    public function getFullDescription(): ?string { return $this->fullDescription; }
    public function setFullDescription(?string $v): static { $this->fullDescription = $v; return $this; }
    public function getImageFile(): ?File { return $this->imageFile; }
    public function setImageFile(?File $file): static { $this->imageFile = $file; if ($file) { $this->updatedAt = new \DateTimeImmutable(); } return $this; }
    public function getImage(): ?string { return $this->image; }
    public function setImage(?string $v): static { $this->image = $v; return $this; }
    public function getIcon(): ?string { return $this->icon; }
    public function setIcon(?string $v): static { $this->icon = $v; return $this; }
    public function getParent(): ?self { return $this->parent; }
    public function setParent(?self $parent): static { $this->parent = $parent; return $this; }
    public function getChildren(): Collection { return $this->children; }
    public function getSortOrder(): ?int { return $this->sortOrder; }
    public function setSortOrder(?int $v): static { $this->sortOrder = $v; return $this; }
    public function isShowOnHome(): bool { return $this->showOnHome; }
    public function setShowOnHome(bool $v): static { $this->showOnHome = $v; return $this; }
    public function isActive(): bool { return $this->active; }
    public function setActive(bool $v): static { $this->active = $v; return $this; }
    public function getSeoTitle(): ?string { return $this->seoTitle; }
    public function setSeoTitle(?string $v): static { $this->seoTitle = $v; return $this; }
    public function getSeoDescription(): ?string { return $this->seoDescription; }
    public function setSeoDescription(?string $v): static { $this->seoDescription = $v; return $this; }
    public function getUpdatedAt(): ?\DateTimeImmutable { return $this->updatedAt; }
    public function getProducts(): Collection { return $this->products; }
}
