<?php

namespace App\Entity;

use App\Repository\ServiceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;


#[ORM\Entity(repositoryClass: ServiceRepository::class)]
class Service
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['service.client'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['service.client'])]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['service.client'])]
    private ?string $description = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['service.client'])]
    private ?int $durationMinute = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['service.client'])]
    private ?float $price = null;

    #[ORM\ManyToOne(inversedBy: 'services')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Business $business = null;

    #[ORM\Column]
    private ?bool $isActive = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $deletedAt = null;

    /**
     * @var Collection<int, AppointmentService>
     */
    #[ORM\OneToMany(targetEntity: AppointmentService::class, mappedBy: 'service')]
    private Collection $appointmentServices;

    #[ORM\ManyToOne(targetEntity: self::class, inversedBy: 'children')]
    private ?self $original = null;

    /**
     * @var Collection<int, self>
     */
    #[ORM\OneToMany(targetEntity: self::class, mappedBy: 'original')]
    private Collection $children;

    public function __construct()
    {
        $this->appointmentServices = new ArrayCollection();
        $this->children = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getDurationMinute(): ?int
    {
        return $this->durationMinute;
    }

    public function setDurationMinute(?int $durationMinute): static
    {
        $this->durationMinute = $durationMinute;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(?float $price): static
    {
        $this->price = $price;

        return $this;
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

    public function isActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): static
    {
        $this->isActive = $isActive;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        //Set timezone of $createdAt
        $createdAt->setTimezone(new \DateTimeZone('UTC'));
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

    /**
     * @return Collection<int, AppointmentService>
     */
    public function getAppointmentServices(): Collection
    {
        return $this->appointmentServices;
    }

    public function addAppointmentService(AppointmentService $appointmentService): static
    {
        if (!$this->appointmentServices->contains($appointmentService)) {
            $this->appointmentServices->add($appointmentService);
            $appointmentService->setService($this);
        }

        return $this;
    }

    public function removeAppointmentService(AppointmentService $appointmentService): static
    {
        if ($this->appointmentServices->removeElement($appointmentService)) {
            // set the owning side to null (unless already changed)
            if ($appointmentService->getService() === $this) {
                $appointmentService->setService(null);
            }
        }

        return $this;
    }

    public function getOriginal(): ?self
    {
        return $this->original;
    }

    public function setOriginal(?self $original): static
    {
        $this->original = $original;

        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getChildren(): Collection
    {
        return $this->children;
    }

    public function addChild(self $child): static
    {
        if (!$this->children->contains($child)) {
            $this->children->add($child);
            $child->setOriginal($this);
        }

        return $this;
    }

    public function removeChild(self $child): static
    {
        if ($this->children->removeElement($child)) {
            // set the owning side to null (unless already changed)
            if ($child->getOriginal() === $this) {
                $child->setOriginal(null);
            }
        }

        return $this;
    }
}
