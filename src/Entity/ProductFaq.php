<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'product_faqs')]
class ProductFaq
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Product::class, inversedBy: 'faqs')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private ?Product $product = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $question = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $answer = null;

    #[ORM\Column(nullable: true)]
    private ?int $sortOrder = 0;

    #[ORM\Column]
    private bool $active = true;

    public function getId(): ?int { return $this->id; }
    public function getProduct(): ?Product { return $this->product; }
    public function setProduct(?Product $product): static { $this->product = $product; return $this; }
    public function getQuestion(): ?string { return $this->question; }
    public function setQuestion(string $question): static { $this->question = $question; return $this; }
    public function getAnswer(): ?string { return $this->answer; }
    public function setAnswer(string $answer): static { $this->answer = $answer; return $this; }
    public function getSortOrder(): ?int { return $this->sortOrder; }
    public function setSortOrder(?int $v): static { $this->sortOrder = $v; return $this; }
    public function isActive(): bool { return $this->active; }
    public function setActive(bool $v): static { $this->active = $v; return $this; }
}
