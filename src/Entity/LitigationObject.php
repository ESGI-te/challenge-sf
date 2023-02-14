<?php

namespace App\Entity;

use App\Repository\LitigationObjectRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Doctrine\UuidGenerator;
use Ramsey\Uuid\UuidInterface;

#[ORM\Entity(repositoryClass: LitigationObjectRepository::class)]
class LitigationObject
{
    #[ORM\Id]
    #[ORM\Column(type: "uuid", unique: true)]
    #[ORM\GeneratedValue(strategy: "CUSTOM")]
    #[ORM\CustomIdGenerator(class: UuidGenerator::class)]
    protected UuidInterface|string $id;

    #[ORM\Column(length: 50)]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $created_at = null;

    #[ORM\OneToMany(mappedBy: 'object', targetEntity: Litigation::class)]
    private Collection $litigations;

    public function __construct()
    {
        $this->litigations = new ArrayCollection();
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeImmutable $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    /**
     * @return Collection<int, Litigation>
     */
    public function getLitigations(): Collection
    {
        return $this->litigations;
    }

    public function addLitigation(Litigation $litigation): self
    {
        if (!$this->litigations->contains($litigation)) {
            $this->litigations->add($litigation);
            $litigation->setObject($this);
        }

        return $this;
    }

    public function removeLitigation(Litigation $litigation): self
    {
        if ($this->litigations->removeElement($litigation)) {
            // set the owning side to null (unless already changed)
            if ($litigation->getObject() === $this) {
                $litigation->setObject(null);
            }
        }

        return $this;
    }
}
