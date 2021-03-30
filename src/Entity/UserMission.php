<?php

namespace App\Entity;

use App\Repository\UserMissionRepository;
use Doctrine\ORM\Mapping as ORM;
use phpDocumentor\Reflection\Types\True_;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=UserMissionRepository::class)
 */
class UserMission
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="userMissions")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity=Mission::class, inversedBy="userMissions")
     */
    private $mission;

    /**
     * @ORM\Column(type="boolean")
     */
    private $done;

    /**
     * @ORM\Column(type="integer")
     */
    private $reward;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isRewarded;

    /**
     * @ORM\Column(type="boolean")
     */
    private $waitingConfirmation;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?UserInterface $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getMission(): ?Mission
    {
        return $this->mission;
    }

    public function setMission(?object $mission): self
    {
        $this->mission = $mission;

        return $this;
    }

    public function getDone(): ?bool
    {
        return $this->done;
    }

    public function setDone(bool $done): self
    {
        $this->done = $done;

        return $this;
    }

    public function __toString(): string
    {
        return $this->getMission()->getTitle();
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

    public function getIsRewarded(): ?bool
    {
        return $this->isRewarded;
    }

    public function setIsRewarded(bool $isRewarded): self
    {
        $this->isRewarded = $isRewarded;

        return $this;
    }

    public function getWaitingConfirmation(): ?bool
    {
        return $this->waitingConfirmation;
    }

    public function setWaitingConfirmation(bool $waitingConfirmation): self
    {
        $this->waitingConfirmation = $waitingConfirmation;

        return $this;
    }
}
