<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'representatives')]
class Representative
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $company = null;

    #[ORM\Column(length: 2)]
    private ?string $state = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $city = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $region = null;

    #[ORM\Column(length: 30, nullable: true)]
    private ?string $phone = null;

    #[ORM\Column(length: 30, nullable: true)]
    private ?string $whatsapp = null;

    #[ORM\Column(length: 150, nullable: true)]
    private ?string $email = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $notes = null;

    #[ORM\Column]
    private bool $active = true;

    public function __toString(): string { return $this->name . ' (' . $this->state . ')'; }

    public function getId(): ?int { return $this->id; }
    public function getName(): ?string { return $this->name; }
    public function setName(string $name): static { $this->name = $name; return $this; }
    public function getCompany(): ?string { return $this->company; }
    public function setCompany(?string $v): static { $this->company = $v; return $this; }
    public function getState(): ?string { return $this->state; }
    public function setState(string $state): static { $this->state = $state; return $this; }
    public function getCity(): ?string { return $this->city; }
    public function setCity(?string $v): static { $this->city = $v; return $this; }
    public function getRegion(): ?string { return $this->region; }
    public function setRegion(?string $v): static { $this->region = $v; return $this; }
    public function getPhone(): ?string { return $this->phone; }
    public function setPhone(?string $v): static { $this->phone = $v; return $this; }
    public function getWhatsapp(): ?string { return $this->whatsapp; }
    public function setWhatsapp(?string $v): static { $this->whatsapp = $v; return $this; }
    public function getEmail(): ?string { return $this->email; }
    public function setEmail(?string $v): static { $this->email = $v; return $this; }
    public function getNotes(): ?string { return $this->notes; }
    public function setNotes(?string $v): static { $this->notes = $v; return $this; }
    public function isActive(): bool { return $this->active; }
    public function setActive(bool $v): static { $this->active = $v; return $this; }
}
