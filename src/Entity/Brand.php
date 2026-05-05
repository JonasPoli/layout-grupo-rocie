<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[ORM\Entity]
#[ORM\Table(name: 'brands')]
#[ORM\HasLifecycleCallbacks]
#[Vich\Uploadable]
class Brand
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255, unique: true)]
    private ?string $slug = null;

    #[Vich\UploadableField(mapping: 'rocie_brand_logo', fileNameProperty: 'logo')]
    private ?File $logoFile = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $logo = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $shortDescription = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $fullDescription = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $officialWebsite = null;

    #[ORM\Column(nullable: true)]
    private ?int $sortOrder = 0;

    #[ORM\Column]
    private bool $showOnHome = false;

    #[ORM\Column]
    private bool $active = true;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\OneToMany(mappedBy: 'brand', targetEntity: Product::class)]
    private Collection $products;

    public function __construct()
    {
        $this->products = new ArrayCollection();
    }

    public function __toString(): string { return $this->name ?? ''; }

    public function getId(): ?int { return $this->id; }
    public function getName(): ?string { return $this->name; }
    public function setName(string $name): static { $this->name = $name; return $this; }
    public function getSlug(): ?string { return $this->slug; }
    public function setSlug(string $slug): static { $this->slug = $slug; return $this; }
    public function getLogoFile(): ?File { return $this->logoFile; }
    public function setLogoFile(?File $file): static { $this->logoFile = $file; if ($file) { $this->updatedAt = new \DateTimeImmutable(); } return $this; }
    public function getLogo(): ?string { return $this->logo; }
    public function setLogo(?string $v): static { $this->logo = $v; return $this; }
    public function getShortDescription(): ?string { return $this->shortDescription; }
    public function setShortDescription(?string $v): static { $this->shortDescription = $v; return $this; }
    public function getFullDescription(): ?string { return $this->fullDescription; }
    public function setFullDescription(?string $v): static { $this->fullDescription = $v; return $this; }
    public function getOfficialWebsite(): ?string { return $this->officialWebsite; }
    public function setOfficialWebsite(?string $v): static { $this->officialWebsite = $v; return $this; }
    public function getSortOrder(): ?int { return $this->sortOrder; }
    public function setSortOrder(?int $v): static { $this->sortOrder = $v; return $this; }
    public function isShowOnHome(): bool { return $this->showOnHome; }
    public function setShowOnHome(bool $v): static { $this->showOnHome = $v; return $this; }
    public function isActive(): bool { return $this->active; }
    public function setActive(bool $v): static { $this->active = $v; return $this; }
    public function getUpdatedAt(): ?\DateTimeImmutable { return $this->updatedAt; }
    public function getProducts(): Collection { return $this->products; }
}
