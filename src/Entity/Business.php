<?php

namespace App\Entity;

use App\Repository\BusinessRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BusinessRepository::class)]
class Business
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 80)]
    private ?string $name = null;

    #[ORM\Column(length: 40)]
    private ?string $username = null;

    #[ORM\Column(length: 40, nullable: true)]
    private ?string $firstname = null;

    #[ORM\Column(length: 40, nullable: true)]
    private ?string $lastname = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $email = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $password = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $location = null;

    /**
     * @var Collection<int, Service>
     */
    #[ORM\OneToMany(targetEntity: Service::class, mappedBy: 'business')]
    private Collection $services;

    /**
     * @var Collection<int, Availability>
     */
    #[ORM\OneToMany(targetEntity: Availability::class, mappedBy: 'business')]
    private Collection $availabilities;

    #[ORM\Column(length: 150)]
    private ?string $timezoneName = null;

    /**
     * @var Collection<int, CategoryService>
     */
    #[ORM\OneToMany(targetEntity: CategoryService::class, mappedBy: 'business')]
    private Collection $categoryServices;

    /**
     * @var Collection<int, Appointment>
     */
    #[ORM\OneToMany(targetEntity: Appointment::class, mappedBy: 'business')]
    private Collection $appointments;

    #[ORM\ManyToOne(inversedBy: 'businesses')]
    private ?User $businessUser = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $createdAt = null;

    public function __construct()
    {
        $this->services = new ArrayCollection();
        $this->availabilities = new ArrayCollection();
        $this->categoryServices = new ArrayCollection();
        $this->appointments = new ArrayCollection();
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

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): static
    {
        $this->username = $username;

        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(?string $firstname): static
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(?string $lastname): static
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(?string $password): static
    {
        $this->password = $password;

        return $this;
    }

    public function getLocation(): ?string
    {
        return $this->location;
    }

    public function setLocation(?string $location): static
    {
        $this->location = $location;

        return $this;
    }

    /**
     * @return Collection<int, Service>
     */
    public function getServices(): Collection
    {
        return $this->services;
    }

    public function addService(Service $service): static
    {
        if (!$this->services->contains($service)) {
            $this->services->add($service);
            $service->setBusiness($this);
        }

        return $this;
    }

    public function removeService(Service $service): static
    {
        if ($this->services->removeElement($service)) {
            // set the owning side to null (unless already changed)
            if ($service->getBusiness() === $this) {
                $service->setBusiness(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Availability>
     */
    public function getAvailabilities(): Collection
    {
        return $this->availabilities;
    }

    public function addAvailability(Availability $availability): static
    {
        if (!$this->availabilities->contains($availability)) {
            $this->availabilities->add($availability);
            $availability->setBusiness($this);
        }

        return $this;
    }

    public function removeAvailability(Availability $availability): static
    {
        if ($this->availabilities->removeElement($availability)) {
            // set the owning side to null (unless already changed)
            if ($availability->getBusiness() === $this) {
                $availability->setBusiness(null);
            }
        }

        return $this;
    }

    public function getTimezoneName(): ?string
    {
        return $this->timezoneName;
    }

    public function setTimezoneName(string $timezoneName): static
    {
        $this->timezoneName = $timezoneName;

        return $this;
    }

    /**
     * @return Collection<int, CategoryService>
     */
    public function getCategoryServices(): Collection
    {
        return $this->categoryServices;
    }

    public function addCategoryService(CategoryService $categoryService): static
    {
        if (!$this->categoryServices->contains($categoryService)) {
            $this->categoryServices->add($categoryService);
            $categoryService->setBusiness($this);
        }

        return $this;
    }

    public function removeCategoryService(CategoryService $categoryService): static
    {
        if ($this->categoryServices->removeElement($categoryService)) {
            // set the owning side to null (unless already changed)
            if ($categoryService->getBusiness() === $this) {
                $categoryService->setBusiness(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Appointment>
     */
    public function getAppointments(): Collection
    {
        return $this->appointments;
    }

    public function addAppointment(Appointment $appointment): static
    {
        if (!$this->appointments->contains($appointment)) {
            $this->appointments->add($appointment);
            $appointment->setBusiness($this);
        }

        return $this;
    }

    public function removeAppointment(Appointment $appointment): static
    {
        if ($this->appointments->removeElement($appointment)) {
            // set the owning side to null (unless already changed)
            if ($appointment->getBusiness() === $this) {
                $appointment->setBusiness(null);
            }
        }

        return $this;
    }

    public function getBusinessUser(): ?User
    {
        return $this->businessUser;
    }

    public function setBusinessUser(?User $businessUser): static
    {
        $this->businessUser = $businessUser;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }
}
