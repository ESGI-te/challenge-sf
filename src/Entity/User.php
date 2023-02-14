<?php

namespace App\Entity;

use App\Repository\UserRepository;
use App\Utilities\Constants;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Doctrine\UuidGenerator;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use App\Utilities\Constants as Consts;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\Column(type: "uuid", unique: true)]
    #[ORM\GeneratedValue(strategy: "CUSTOM")]
    #[ORM\CustomIdGenerator(class: UuidGenerator::class)]
    protected UuidInterface|string $id;

    #[ORM\Column(length: 180, unique: true)]
    private ?string $email = null;

    #[ORM\Column]
    private array $roles = [Consts::ROLES['USER']];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(length: 50)]
    private ?string $firstname = null;

    #[ORM\Column(length: 50)]
    private ?string $lastname = null;

    #[ORM\Column(length: 500, nullable: true)]
    private ?string $avatar = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $created_at = null;

    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $nb_warnings = 0;

    #[ORM\Column(length: 255)]
    private ?string $token = null;

    #[ORM\OneToOne(inversedBy: 'user_id', cascade: ['persist', 'remove'])]
    private ?ModeratorRequest $moderatorRequest = null;

    #[ORM\OneToMany(mappedBy: 'user_id', targetEntity: Music::class, orphanRemoval: true)]
    private Collection $musics;

    #[ORM\OneToMany(mappedBy: 'user_id', targetEntity: Playlist::class, orphanRemoval: true)]
    private Collection $playlists;

    #[ORM\OneToMany(mappedBy: 'user_id', targetEntity: Litigation::class, orphanRemoval: true)]
    private Collection $litigations;

    #[ORM\Column(length: 50)]
    private ?string $username = null;

    #[ORM\OneToMany(mappedBy: 'user_id', targetEntity: Like::class, orphanRemoval: true)]
    private Collection $likes;

    public function __construct()
    {
        $this->musics = new ArrayCollection();
        $this->playlists = new ArrayCollection();
        $this->litigations = new ArrayCollection();
        $this->likes = new ArrayCollection();
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getAvatar(): ?string
    {
        return $this->avatar;
    }

    public function setAvatar(?string $avatar): self
    {
        $this->avatar = $avatar;

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

    public function getNbWarnings(): ?int
    {
        return $this->nb_warnings;
    }

    public function setNbWarnings(int $nb_warnings): self
    {
        $this->nb_warnings = $nb_warnings;

        return $this;
    }

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function setToken(string $token): self
    {
        $this->token = $token;

        return $this;
    }

    public function getModeratorRequest(): ?ModeratorRequest
    {
        return $this->moderatorRequest;
    }

    public function setModeratorRequest(?ModeratorRequest $moderatorRequest): self
    {
        $this->moderatorRequest = $moderatorRequest;

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
            $litigation->setUserId($this);
        }

        return $this;
    }

    public function removeLitigation(Litigation $litigation): self
    {
        if ($this->litigations->removeElement($litigation)) {
            // set the owning side to null (unless already changed)
            if ($litigation->getUserId() === $this) {
                $litigation->setUserId(null);
            }
        }

        return $this;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    /**
     * @return Collection<int, Like>
     */
    public function getLikes(): Collection
    {
        return $this->likes;
    }

    public function addLike(Like $like): self
    {
        if (!$this->likes->contains($like)) {
            $this->likes->add($like);
            $like->setUserId($this);
        }

        return $this;
    }

    public function removeLike(Like $like): self
    {
        if ($this->likes->removeElement($like)) {
            // set the owning side to null (unless already changed)
            if ($like->getUserId() === $this) {
                $like->setUserId(null);
            }
        }

        return $this;
    }
}
