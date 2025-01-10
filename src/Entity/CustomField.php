<?php

namespace App\Entity;

use App\Repository\CustomFieldRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CustomFieldRepository::class)]
class CustomField
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $label = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $value = null;

    #[ORM\ManyToOne(inversedBy: 'customFields')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Contact $Contact = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(?string $label): static
    {
        $this->label = $label;

        return $this;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function setValue(?string $value): static
    {
        $this->value = $value;

        return $this;
    }

    public function getContact(): ?Contact
    {
        return $this->Contact;
    }

    public function setContact(?Contact $Contact): static
    {
        $this->Contact = $Contact;

        return $this;
    }
}
