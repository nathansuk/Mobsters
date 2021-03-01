<?php

namespace App\Entity;

use App\Repository\NewsCategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=NewsCategoryRepository::class)
 */
class NewsCategory
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
    private $name;

    /**
     * @ORM\OneToMany(targetEntity=News::class, mappedBy="category")
     */
    private $newsId;

    public function __construct()
    {
        $this->newsId = new ArrayCollection();
    }

    public function getId(): ?int
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

    /**
     * @return Collection|News[]
     */
    public function getNewsId(): Collection
    {
        return $this->newsId;
    }

    public function addNewsId(News $newsId): self
    {
        if (!$this->newsId->contains($newsId)) {
            $this->newsId[] = $newsId;
            $newsId->setCategory($this);
        }

        return $this;
    }

    public function removeNewsId(News $newsId): self
    {
        if ($this->newsId->removeElement($newsId)) {
            // set the owning side to null (unless already changed)
            if ($newsId->getCategory() === $this) {
                $newsId->setCategory(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->name;
    }
}
