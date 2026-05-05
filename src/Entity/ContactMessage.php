<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'contact_messages')]
#[ORM\HasLifecycleCallbacks]
class ContactMessage
{
    const STATUS_NEW = 'nova';
    const STATUS_IN_PROGRESS = 'em_atendimento';
    const STATUS_ANSWERED = 'respondida';
    const STATUS_ARCHIVED = 'arquivada';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $company = null;

    #[ORM\Column(length: 30, nullable: true)]
    private ?string $document = null;

    #[ORM\Column(length: 150)]
    private ?string $email = null;

    #[ORM\Column(length: 30, nullable: true)]
    private ?string $phone = null;

    #[ORM\Column(length: 30, nullable: true)]
    private ?string $whatsapp = null;

    #[ORM\Column(length: 2, nullable: true)]
    private ?string $state = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $city = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $subject = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $department = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $message = null;

    #[ORM\Column(length: 30)]
    private string $status = self::STATUS_NEW;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $formType = 'contact';

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\PrePersist]
    public function onPrePersist(): void
    {
        $this->createdAt = new \DateTimeImmutable();
    }

    public function getId(): ?int { return $this->id; }
    public function getName(): ?string { return $this->name; }
    public function setName(string $name): static { $this->name = $name; return $this; }
    public function getCompany(): ?string { return $this->company; }
    public function setCompany(?string $v): static { $this->company = $v; return $this; }
    public function getDocument(): ?string { return $this->document; }
    public function setDocument(?string $v): static { $this->document = $v; return $this; }
    public function getEmail(): ?string { return $this->email; }
    public function setEmail(string $email): static { $this->email = $email; return $this; }
    public function getPhone(): ?string { return $this->phone; }
    public function setPhone(?string $v): static { $this->phone = $v; return $this; }
    public function getWhatsapp(): ?string { return $this->whatsapp; }
    public function setWhatsapp(?string $v): static { $this->whatsapp = $v; return $this; }
    public function getState(): ?string { return $this->state; }
    public function setState(?string $v): static { $this->state = $v; return $this; }
    public function getCity(): ?string { return $this->city; }
    public function setCity(?string $v): static { $this->city = $v; return $this; }
    public function getSubject(): ?string { return $this->subject; }
    public function setSubject(?string $v): static { $this->subject = $v; return $this; }
    public function getDepartment(): ?string { return $this->department; }
    public function setDepartment(?string $v): static { $this->department = $v; return $this; }
    public function getMessage(): ?string { return $this->message; }
    public function setMessage(string $message): static { $this->message = $message; return $this; }
    public function getStatus(): string { return $this->status; }
    public function setStatus(string $status): static { $this->status = $status; return $this; }
    public function getFormType(): ?string { return $this->formType; }
    public function setFormType(?string $v): static { $this->formType = $v; return $this; }
    public function getCreatedAt(): ?\DateTimeImmutable { return $this->createdAt; }
}
