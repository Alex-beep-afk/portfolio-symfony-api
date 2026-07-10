<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\MessageContactRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;


#[ORM\Entity(repositoryClass: MessageContactRepository::class)]
#[ApiResource(
    normalizationContext: ["groups" => ["contact:read"]],
    denormalizationContext: ["groups" => ["contact:write"]]
)]
class MessageContact
{
    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
    }

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(["contact:read"])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(["contact:read", "contact:write"])]
    private ?string $object = null;

    #[ORM\Column(length: 1000)]
    #[Groups(["contact:read", "contact:write"])]
    private ?string $content = null;

    #[ORM\Column(length: 280)]
    #[Groups(["contact:read", "contact:write"])]
    private ?string $mail = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(["contact:read", "contact:write"])]
    private ?string $phoneNumber = null;

    #[ORM\Column]
    #[Groups(["contact:read"])]
    private ?\DateTimeImmutable $createdAt = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getObject(): ?string
    {
        return $this->object;
    }

    public function setObject(string $object): static
    {
        $this->object = $object;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): static
    {
        $this->content = $content;

        return $this;
    }

    public function getMail(): ?string
    {
        return $this->mail;
    }

    public function setMail(string $mail): static
    {
        $this->mail = $mail;

        return $this;
    }

    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(?string $phoneNumber): static
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        if ($this->createdAt === null) {
            $this->createdAt = new \DateTimeImmutable();
        }
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }


}
