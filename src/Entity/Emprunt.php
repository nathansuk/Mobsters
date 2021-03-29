<?php

namespace App\Entity;

use App\Repository\EmpruntRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=EmpruntRepository::class)
 */
class Emprunt
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="emprunts")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $motif;

    /**
     * @ORM\Column(type="integer")
     */
    private $montant;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isAccepted;

    /**
     * @ORM\Column(type="float")
     */
    private $interets;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isReimbursed;

    /**
     * @ORM\Column(type="date")
     */
    private $date;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $acceptedBy;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $validateBy;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getMotif(): ?string
    {
        return $this->motif;
    }

    public function setMotif(string $motif): self
    {
        $this->motif = $motif;

        return $this;
    }

    public function getMontant(): ?int
    {
        return $this->montant;
    }

    public function setMontant(int $montant): self
    {
        $this->montant = $montant;

        return $this;
    }

    public function getIsAccepted(): ?bool
    {
        return $this->isAccepted;
    }

    public function setIsAccepted(bool $isAccepted): self
    {
        $this->isAccepted = $isAccepted;

        return $this;
    }

    public function getInterets(): ?float
    {
        return $this->interets;
    }

    public function setInterets(float $interets): self
    {
        $this->interets = $interets;

        return $this;
    }

    public function getIsReimbursed(): ?bool
    {
        return $this->isReimbursed;
    }

    public function setIsReimbursed(bool $isReimbursed): self
    {
        $this->isReimbursed = $isReimbursed;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getAcceptedBy(): ?string
    {
        return $this->acceptedBy;
    }

    public function setAcceptedBy(?string $acceptedBy): self
    {
        $this->acceptedBy = $acceptedBy;

        return $this;
    }

    public function getValidateBy(): ?string
    {
        return $this->validateBy;
    }

    public function setValidateBy(?string $validateBy): self
    {
        $this->validateBy = $validateBy;

        return $this;
    }

}
