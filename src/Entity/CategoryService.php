<?php

namespace App\Entity;

use App\Repository\CategoryServiceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CategoryServiceRepository::class)]
class CategoryService
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'categoryServices')]
    private ?Business $business = null;

    #[ORM\Column(length: 150)]
    private ?string $name = null;

    #[ORM\ManyToOne(targetEntity: self::class, inversedBy: 'categoryServiceChildren')]
    private ?self $originalId = null;

    /**
     * @var Collection<int, self>
     */
    #[ORM\OneToMany(targetEntity: self::class, mappedBy: 'originalId')]
    private Collection $categoryServiceChildren;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $deletedAt = null;

    public function __construct()
    {
        $this->categoryServicsChildren = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBusiness(): ?Business
    {
        return $this->business;
    }

    public function setBusiness(?Business $business): static
    {
        $this->business = $business;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getOriginalId(): ?self
    {
        return $this->originalId;
    }

    public function setOriginalId(?self $originalId): static
    {
        $this->originalId = $originalId;

        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getCategoryServiceChildren(): Collection
    {
        return $this->categoryServiceChildren;
    }

    public function addCategoryServiceChild(self $categoryServiceChild): static
    {
        if (!$this->categoryServiceChildren->contains($categoryServiceChild)) {
            $this->categoryServiceChildren->add($categoryServiceChild);
            $categoryServiceChild->setOriginalId($this);
        }

        return $this;
    }

    public function removeCategoryServiceChild(self $categoryServiceChild): static
    {
        if ($this->categoryServiceChildren->removeElement($categoryServiceChild)) {
            // set the owning side to null (unless already changed)
            if ($categoryServiceChild->getOriginalId() === $this) {
                $categoryServiceChild->setOriginalId(null);
            }
        }

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getDeletedAt(): ?\DateTimeImmutable
    {
        return $this->deletedAt;
    }

    public function setDeletedAt(?\DateTimeImmutable $deletedAt): static
    {
        $this->deletedAt = $deletedAt;

        return $this;
    }
}
