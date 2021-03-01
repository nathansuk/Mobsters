<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @UniqueEntity(fields = "email", message="Cette adresse mail est déjà utilisée")
 * @UniqueEntity(fields = "username", message="Ce pseudo est déjà utilisé")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", unique=true)
     * @Assert\Length(min="3", minMessage="Votre nom d'utilisateur doit faire plus de 3 caractères")
     * @Assert\Length(max="32", maxMessage="Votre nom d'utilisateur ne doit pas faire plus de 32 caractères")
     */
    private $username;


    /**
     * @ORM\Column(type="string", length=255, unique=true)
     * @Assert\Email(message="Cette adresse email est invalide")
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     * @Assert\Length(min="8", minMessage="Votre mot de passe doit faire plus de 8 caractères")
     */
    private $password;

    /**
     * @Assert\EqualTo(propertyPath="password", message="Les deux mots de passe ne sont pas identiques")
     */
    public $confirm_password;

    /**
     * @ORM\Column(type="integer")
     */
    private $money;

    /**
     * @ORM\Column(type="date")
     */
    private $createdAt;

    /**
     * @ORM\OneToMany(targetEntity=Clan::class, mappedBy="user")
     */
    private $clan;

    public function __construct()
    {
        $this->clan = new ArrayCollection();
    }

    public function getId(): ?int
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
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getMoney(): ?int
    {
        return $this->money;
    }

    public function setMoney(int $money): self
    {
        $this->money = $money;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @return Collection|Clan[]
     */
    public function getClan(): Collection
    {
        return $this->clan;
    }

    public function addClan(Clan $clan): self
    {
        if (!$this->clan->contains($clan)) {
            $this->clan[] = $clan;
            $clan->setUser($this);
        }

        return $this;
    }

    public function removeClan(Clan $clan): self
    {
        if ($this->clan->removeElement($clan)) {
            // set the owning side to null (unless already changed)
            if ($clan->getUser() === $this) {
                $clan->setUser(null);
            }
        }

        return $this;
    }
}
