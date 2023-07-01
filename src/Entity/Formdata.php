<?php

namespace App\Entity;

use App\Repository\FormdataRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

#[ORM\Entity(repositoryClass: FormdataRepository::class)]
class Formdata
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\Length(
        min: 3,
        minMessage: 'Title should not be blank'
    )]
    private ?string $title = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $createdAt = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $email = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $content = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $location = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $specificLocationName = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    #[Assert\Callback]
    public function validate(ExecutionContextInterface $context, $payload)
    {
        // ...
        if(stripos($this->getTitle(), 'borg') !== false) {
            $context->buildViolation('Borgs are not allowed')
            ->atPath('title')
            ->addViolation();
        }
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?User $user): static
    {
        $this->email = $user->getEmail();

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(?string $content): static
    {
        $this->content = $content;

        return $this;
    }

    public function getLocation(): ?string
    {
        return $this->location;
    }

    public function setLocation(?string $location): static
    {
        $this->location = $location;
        if (!$this->location || $this->location === 'interstellar_space') {
            $this->setSpecificLocationName(null);
        }
        return $this;
    }

    public function getSpecificLocationName(): ?string
    {
        return $this->specificLocationName;
    }

    public function setSpecificLocationName(?string $specificLocationName): static
    {
        $this->specificLocationName = $specificLocationName;

        return $this;
    }
}
