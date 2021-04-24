<?php

namespace App\Entity;

use App\Repository\MissionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MissionRepository::class)
 */
class Mission
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $type;

    /**
     * @ORM\ManyToOne(targetEntity=Clans::class, inversedBy="missions")
     */
    private $clan;

    /**
     * @ORM\OneToMany(targetEntity=UserMission::class, mappedBy="mission")
     */
    private $userMissions;

    /**
     * @ORM\Column(type="integer")
     */
    private $reward;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $diamondsReward;

    public function __construct()
    {
        $this->userMissions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

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

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getClan(): ?Clans
    {
        return $this->clan;
    }

    public function setClan(?Clans $clan): self
    {
        $this->clan = $clan;

        return $this;
    }

    /**
     * @return Collection|UserMission[]
     */
    public function getUserMissions(): Collection
    {
        return $this->userMissions;
    }

    public function addUserMission(UserMission $userMission): self
    {
        if (!$this->userMissions->contains($userMission)) {
            $this->userMissions[] = $userMission;
            $userMission->setMission($this);
        }

        return $this;
    }

    public function removeUserMission(UserMission $userMission): self
    {
        if ($this->userMissions->removeElement($userMission)) {
            // set the owning side to null (unless already changed)
            if ($userMission->getMission() === $this) {
                $userMission->setMission(null);
            }
        }

        return $this;
    }
    
    public function __toString(): string {
        return $this->title;
    }

    public function getReward(): ?int
    {
        return $this->reward;
    }

    public function setReward(int $reward): self
    {
        $this->reward = $reward;

        return $this;
    }

    public function getDiamondsReward(): ?int
    {
        return $this->diamondsReward;
    }

    public function setDiamondsReward(?int $diamondsReward): self
    {
        $this->diamondsReward = $diamondsReward;

        return $this;
    }
}
