<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;

use App\Repository\ProjectRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Symfony\Component\Serializer\Attribute\Groups;
use Doctrine\ORM\Mapping as ORM;

#[ApiResource(
    normalizationContext: ["groups" => ["project:read"]],
    denormalizationContext: ["groups" => ["project:write"]]
)]
#[ORM\Entity(repositoryClass: ProjectRepository::class)]
class Project
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['project:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['project:read', 'project:write'])]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Groups(['project:read', 'project:write'])]
    private ?string $description = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Groups(['project:read', 'project:write'])]
    private ?string $difficulties = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['project:read', 'project:write'])]
    private ?string $link = null;

    /**
     * @var Collection<int, Techno>
     */
    #[ORM\ManyToMany(targetEntity: Techno::class, inversedBy: 'projects')]
    #[Groups(['project:read', 'project:write'])]
    private Collection $techno;

    #[ORM\ManyToOne]
    #[Groups(['project:read', 'project:write'])]
    private ?MediaObject $coverImage = null;

    /**
     * @var Collection<int, MediaObject>
     */
    #[ORM\ManyToMany(targetEntity: MediaObject::class)]
    #[Groups(['project:read', 'project:write'])]
    private Collection $gallery;

    public function __construct()
    {
        $this->techno = new ArrayCollection();
        $this->gallery = new ArrayCollection();
    }

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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getLink(): ?string
    {
        return $this->link;
    }

    public function setLink(?string $link): static
    {
        $this->link = $link;

        return $this;
    }

    /**
     * Get the value of difficulties
     */
    public function getDifficulties(): ?string
    {
        return $this->difficulties;
    }

    /**
     * Set the value of difficulties
     */
    public function setDifficulties(?string $difficulties): self
    {
        $this->difficulties = $difficulties;

        return $this;
    }

    /**
     * @return Collection<int, Techno>
     */
    public function getTechno(): Collection
    {
        return $this->techno;
    }

    public function addTechno(Techno $techno): static
    {
        if (!$this->techno->contains($techno)) {
            $this->techno->add($techno);
        }

        return $this;
    }

    public function removeTechno(Techno $techno): static
    {
        $this->techno->removeElement($techno);

        return $this;
    }

    public function getCoverImage(): ?MediaObject
    {
        return $this->coverImage;
    }

    public function setCoverImage(?MediaObject $coverImage): static
    {
        $this->coverImage = $coverImage;

        return $this;
    }

    /**
     * @return Collection<int, MediaObject>
     */
    public function getGallery(): Collection
    {
        return $this->gallery;
    }

    public function addGallery(MediaObject $gallery): static
    {
        if (!$this->gallery->contains($gallery)) {
            $this->gallery->add($gallery);
        }

        return $this;
    }

    public function removeGallery(MediaObject $gallery): static
    {
        $this->gallery->removeElement($gallery);

        return $this;
    }
}
