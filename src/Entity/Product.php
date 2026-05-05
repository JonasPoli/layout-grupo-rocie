<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[ORM\Entity]
#[ORM\Table(name: 'products')]
#[ORM\HasLifecycleCallbacks]
#[Vich\Uploadable]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255, unique: true)]
    private ?string $slug = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $subtitle = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $internalCode = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $sku = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $ean = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $shortDescription = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $fullDescription = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $summary = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $aboutItems = null; // Bullet list "Sobre este item"

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $benefits = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $differentials = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $usageIndication = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $material = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $composition = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $dimensions = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $weight = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $color = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $size = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $capacity = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $packaging = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $warranty = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $origin = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 3, scale: 1, nullable: true)]
    private ?string $ratingAverage = null;

    #[ORM\Column(nullable: true)]
    private ?int $ratingCount = null;

    #[ORM\Column]
    private bool $active = true;

    #[ORM\Column]
    private bool $isFeatured = false;

    #[ORM\Column]
    private bool $isNew = false;

    #[ORM\Column]
    private bool $isPromotional = false;

    #[ORM\Column(nullable: true)]
    private ?int $sortOrder = 0;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $seoTitle = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $seoDescription = null;

    // Main image via VichUploader
    #[Vich\UploadableField(mapping: 'rocie_product_image', fileNameProperty: 'mainImage')]
    private ?File $mainImageFile = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $mainImage = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    // Relations
    #[ORM\ManyToOne(targetEntity: Brand::class, inversedBy: 'products')]
    private ?Brand $brand = null;

    #[ORM\ManyToOne(targetEntity: Category::class, inversedBy: 'products')]
    private ?Category $mainCategory = null;

    #[ORM\ManyToMany(targetEntity: Category::class)]
    #[ORM\JoinTable(name: 'product_categories')]
    private Collection $categories;

    #[ORM\OneToMany(mappedBy: 'product', targetEntity: ProductImage::class, cascade: ['persist', 'remove'], orphanRemoval: true)]
    #[ORM\OrderBy(['sortOrder' => 'ASC'])]
    private Collection $images;

    #[ORM\OneToMany(mappedBy: 'product', targetEntity: ProductVariation::class, cascade: ['persist', 'remove'], orphanRemoval: true)]
    private Collection $variations;

    #[ORM\OneToMany(mappedBy: 'product', targetEntity: ProductFaq::class, cascade: ['persist', 'remove'], orphanRemoval: true)]
    #[ORM\OrderBy(['sortOrder' => 'ASC'])]
    private Collection $faqs;

    #[ORM\OneToMany(mappedBy: 'product', targetEntity: ProductReview::class, cascade: ['persist', 'remove'], orphanRemoval: true)]
    #[ORM\OrderBy(['reviewedAt' => 'DESC'])]
    private Collection $reviews;

    #[ORM\ManyToMany(targetEntity: self::class)]
    #[ORM\JoinTable(name: 'product_related')]
    private Collection $relatedProducts;

    public function __construct()
    {
        $this->categories = new ArrayCollection();
        $this->images = new ArrayCollection();
        $this->variations = new ArrayCollection();
        $this->faqs = new ArrayCollection();
        $this->reviews = new ArrayCollection();
        $this->relatedProducts = new ArrayCollection();
        $this->createdAt = new \DateTimeImmutable();
    }

    #[ORM\PrePersist]
    public function onPrePersist(): void
    {
        $this->createdAt = new \DateTimeImmutable();
        $this->updatedAt = new \DateTimeImmutable();
    }

    #[ORM\PreUpdate]
    public function onPreUpdate(): void
    {
        $this->updatedAt = new \DateTimeImmutable();
    }

    public function __toString(): string { return $this->name ?? ''; }

    public function getId(): ?int { return $this->id; }
    public function getName(): ?string { return $this->name; }
    public function setName(string $name): static { $this->name = $name; return $this; }
    public function getSlug(): ?string { return $this->slug; }
    public function setSlug(string $slug): static { $this->slug = $slug; return $this; }
    public function getSubtitle(): ?string { return $this->subtitle; }
    public function setSubtitle(?string $v): static { $this->subtitle = $v; return $this; }
    public function getInternalCode(): ?string { return $this->internalCode; }
    public function setInternalCode(?string $v): static { $this->internalCode = $v; return $this; }
    public function getSku(): ?string { return $this->sku; }
    public function setSku(?string $v): static { $this->sku = $v; return $this; }
    public function getEan(): ?string { return $this->ean; }
    public function setEan(?string $v): static { $this->ean = $v; return $this; }
    public function getShortDescription(): ?string { return $this->shortDescription; }
    public function setShortDescription(?string $v): static { $this->shortDescription = $v; return $this; }
    public function getFullDescription(): ?string { return $this->fullDescription; }
    public function setFullDescription(?string $v): static { $this->fullDescription = $v; return $this; }
    public function getAboutItems(): ?string { return $this->aboutItems; }
    public function setAboutItems(?string $v): static { $this->aboutItems = $v; return $this; }
    public function getSummary(): ?string { return $this->summary; }
    public function setSummary(?string $v): static { $this->summary = $v; return $this; }
    public function getBenefits(): ?string { return $this->benefits; }
    public function setBenefits(?string $v): static { $this->benefits = $v; return $this; }
    public function getDifferentials(): ?string { return $this->differentials; }
    public function setDifferentials(?string $v): static { $this->differentials = $v; return $this; }
    public function getUsageIndication(): ?string { return $this->usageIndication; }
    public function setUsageIndication(?string $v): static { $this->usageIndication = $v; return $this; }
    public function getMaterial(): ?string { return $this->material; }
    public function setMaterial(?string $v): static { $this->material = $v; return $this; }
    public function getComposition(): ?string { return $this->composition; }
    public function setComposition(?string $v): static { $this->composition = $v; return $this; }
    public function getDimensions(): ?string { return $this->dimensions; }
    public function setDimensions(?string $v): static { $this->dimensions = $v; return $this; }
    public function getWeight(): ?string { return $this->weight; }
    public function setWeight(?string $v): static { $this->weight = $v; return $this; }
    public function getColor(): ?string { return $this->color; }
    public function setColor(?string $v): static { $this->color = $v; return $this; }
    public function getSize(): ?string { return $this->size; }
    public function setSize(?string $v): static { $this->size = $v; return $this; }
    public function getCapacity(): ?string { return $this->capacity; }
    public function setCapacity(?string $v): static { $this->capacity = $v; return $this; }
    public function getPackaging(): ?string { return $this->packaging; }
    public function setPackaging(?string $v): static { $this->packaging = $v; return $this; }
    public function getWarranty(): ?string { return $this->warranty; }
    public function setWarranty(?string $v): static { $this->warranty = $v; return $this; }
    public function getOrigin(): ?string { return $this->origin; }
    public function setOrigin(?string $v): static { $this->origin = $v; return $this; }
    public function getRatingAverage(): ?string { return $this->ratingAverage; }
    public function setRatingAverage(?string $v): static { $this->ratingAverage = $v; return $this; }
    public function getRatingCount(): ?int { return $this->ratingCount; }
    public function setRatingCount(?int $v): static { $this->ratingCount = $v; return $this; }
    public function isActive(): bool { return $this->active; }
    public function setActive(bool $v): static { $this->active = $v; return $this; }
    public function isFeatured(): bool { return $this->isFeatured; }
    public function setIsFeatured(bool $v): static { $this->isFeatured = $v; return $this; }
    public function isNew(): bool { return $this->isNew; }
    public function setIsNew(bool $v): static { $this->isNew = $v; return $this; }
    public function isPromotional(): bool { return $this->isPromotional; }
    public function setIsPromotional(bool $v): static { $this->isPromotional = $v; return $this; }
    public function getSortOrder(): ?int { return $this->sortOrder; }
    public function setSortOrder(?int $v): static { $this->sortOrder = $v; return $this; }
    public function getSeoTitle(): ?string { return $this->seoTitle; }
    public function setSeoTitle(?string $v): static { $this->seoTitle = $v; return $this; }
    public function getSeoDescription(): ?string { return $this->seoDescription; }
    public function setSeoDescription(?string $v): static { $this->seoDescription = $v; return $this; }
    public function getMainImageFile(): ?File { return $this->mainImageFile; }
    public function setMainImageFile(?File $file): static { $this->mainImageFile = $file; if ($file) { $this->updatedAt = new \DateTimeImmutable(); } return $this; }
    public function getMainImage(): ?string { return $this->mainImage; }
    public function setMainImage(?string $v): static { $this->mainImage = $v; return $this; }
    public function getCreatedAt(): ?\DateTimeImmutable { return $this->createdAt; }
    public function getUpdatedAt(): ?\DateTimeImmutable { return $this->updatedAt; }
    public function getBrand(): ?Brand { return $this->brand; }
    public function setBrand(?Brand $brand): static { $this->brand = $brand; return $this; }
    public function getMainCategory(): ?Category { return $this->mainCategory; }
    public function setMainCategory(?Category $cat): static { $this->mainCategory = $cat; return $this; }
    public function getCategories(): Collection { return $this->categories; }
    public function addCategory(Category $c): static { if (!$this->categories->contains($c)) { $this->categories->add($c); } return $this; }
    public function removeCategory(Category $c): static { $this->categories->removeElement($c); return $this; }
    public function getImages(): Collection { return $this->images; }
    public function addImage(ProductImage $img): static { if (!$this->images->contains($img)) { $this->images->add($img); $img->setProduct($this); } return $this; }
    public function removeImage(ProductImage $img): static { if ($this->images->removeElement($img)) { if ($img->getProduct() === $this) { $img->setProduct(null); } } return $this; }
    public function getVariations(): Collection { return $this->variations; }
    public function addVariation(ProductVariation $v): static { if (!$this->variations->contains($v)) { $this->variations->add($v); $v->setProduct($this); } return $this; }
    public function getFaqs(): Collection { return $this->faqs; }
    public function addFaq(ProductFaq $f): static { if (!$this->faqs->contains($f)) { $this->faqs->add($f); $f->setProduct($this); } return $this; }
    public function getReviews(): Collection { return $this->reviews; }
    public function addReview(ProductReview $r): static { if (!$this->reviews->contains($r)) { $this->reviews->add($r); $r->setProduct($this); } return $this; }
    public function removeReview(ProductReview $r): static { if ($this->reviews->removeElement($r)) { if ($r->getProduct() === $this) { $r->setProduct(null); } } return $this; }
    public function getRelatedProducts(): Collection { return $this->relatedProducts; }
}
