<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'product_reviews')]
#[ORM\HasLifecycleCallbacks]
class ProductReview
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Product::class, inversedBy: 'reviews')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private ?Product $product = null;

    #[ORM\Column(length: 255)]
    private ?string $authorName = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $authorLocation = null;

    #[ORM\Column]
    private int $rating = 5; // 1 to 5

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $body = null;

    #[ORM\Column]
    private bool $verified = true;

    #[ORM\Column]
    private bool $active = true;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $reviewedAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\PrePersist]
    public function onPrePersist(): void
    {
        $this->createdAt = new \DateTimeImmutable();
        if (!$this->reviewedAt) {
            $this->reviewedAt = new \DateTimeImmutable();
        }
    }

    public function getId(): ?int { return $this->id; }
    public function getProduct(): ?Product { return $this->product; }
    public function setProduct(?Product $product): static { $this->product = $product; return $this; }
    public function getAuthorName(): ?string { return $this->authorName; }
    public function setAuthorName(string $v): static { $this->authorName = $v; return $this; }
    public function getAuthorLocation(): ?string { return $this->authorLocation; }
    public function setAuthorLocation(?string $v): static { $this->authorLocation = $v; return $this; }
    public function getRating(): int { return $this->rating; }
    public function setRating(int $v): static { $this->rating = max(1, min(5, $v)); return $this; }
    public function getTitle(): ?string { return $this->title; }
    public function setTitle(?string $v): static { $this->title = $v; return $this; }
    public function getBody(): ?string { return $this->body; }
    public function setBody(string $v): static { $this->body = $v; return $this; }
    public function isVerified(): bool { return $this->verified; }
    public function setVerified(bool $v): static { $this->verified = $v; return $this; }
    public function isActive(): bool { return $this->active; }
    public function setActive(bool $v): static { $this->active = $v; return $this; }
    public function getReviewedAt(): ?\DateTimeImmutable { return $this->reviewedAt; }
    public function setReviewedAt(?\DateTimeImmutable $v): static { $this->reviewedAt = $v; return $this; }
    public function getCreatedAt(): ?\DateTimeImmutable { return $this->createdAt; }
}
