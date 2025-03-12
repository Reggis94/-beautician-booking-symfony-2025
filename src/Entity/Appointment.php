<?php

namespace App\Entity;

use App\Repository\AppointmentRepository;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AppointmentRepository::class)]
class Appointment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $startDateTimeUtc = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $endDateTimeUtc = null;

    #[ORM\Column(length: 255)]
    private ?string $timezone = null;

    #[ORM\Column(nullable: true)]
    private ?float $price = null;

    #[ORM\Column(nullable: false)]
    private ?\DateTimeImmutable $createdAt = null;

    /**
     * @var Collection<int, AppointmentService>
     */
    #[ORM\OneToMany(targetEntity: AppointmentService::class, mappedBy: 'appointment')]
    private Collection $appointmentServices;

    /**
     * @var Collection<int, Invoice>
     */
    #[ORM\OneToMany(targetEntity: Invoice::class, mappedBy: 'appointment')]
    private Collection $invoices;

    #[ORM\Column(length: 7, nullable: true)]
    private ?string $phoneCountryCode = null;

    #[ORM\Column(length: 20, nullable: true)]
    private ?string $phoneNumber = null;

    #[ORM\Column(length: 50)]
    private ?string $firstName = null;

    #[ORM\Column(length: 50)]
    private ?string $lastName = null;

    #[ORM\Column(length: 100)]
    private ?string $email = null;

    #[ORM\Column]
    private ?int $duration_minutes = null;

    private \DateTimeImmutable $startDateTimeConvertedToTimezone;

    #[ORM\ManyToOne(inversedBy: 'appointments')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Business $business = null;

    public function __construct()
    {
        $this->appointmentServices = new ArrayCollection();
        $this->invoices = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStartDateTimeUtc(): ?\DateTimeImmutable
    {
        return $this->startDateTimeUtc;
    }

    public function setStartDateTimeUtc(\DateTimeImmutable $startDateTimeUtc): static
    {
        $this->startDateTimeUtc = $startDateTimeUtc;

        return $this;
    }

    public function getStartDateTimeConvertedToTimeZone(): ?\DateTimeImmutable
    {
        return $this->startDateTimeUtc->setTimezone(new \DateTimeZone($this->timezone));
    }

    // public function setStartDateTimeConvertedToTimeZone(\DateTimeImmutable $startDateTimeConvertedToTimezone): static
    // {
    //     $this->startDateTimeConvertedToTimeZone = $startDateTimeConvertedToTimezone;
        
    //     //Convert to UTC time
    //     $this->startDateTimeUtc = $startDateTimeConvertedToTimezone->setTimezone(new \DateTimeZone('UTC'));
    //     return $this;
    // }

    

    public function getEndDateTimeUtc(): ?\DateTimeImmutable
    {
        return $this->endDateTimeUtc;
    }

    public function setEndDateTimeUtc(?\DateTimeImmutable $endDateTimeUtc): static
    {
        $this->endDateTimeUtc = $endDateTimeUtc;

        return $this;
    }

    public function getTimezone(): ?string
    {
        return $this->timezone;
    }

    public function setTimezone(string $timezone): static
    {
        $this->timezone = $timezone;

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
            $appointmentService->setAppointment($this);
        }

        return $this;
    }

    public function removeAppointmentService(AppointmentService $appointmentService): static
    {
        if ($this->appointmentServices->removeElement($appointmentService)) {
            // set the owning side to null (unless already changed)
            if ($appointmentService->getAppointment() === $this) {
                $appointmentService->setAppointment(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Invoice>
     */
    public function getInvoices(): Collection
    {
        return $this->invoices;
    }

    public function addInvoice(Invoice $invoice): static
    {
        if (!$this->invoices->contains($invoice)) {
            $this->invoices->add($invoice);
            $invoice->setAppointment($this);
        }

        return $this;
    }

    public function removeInvoice(Invoice $invoice): static
    {
        if ($this->invoices->removeElement($invoice)) {
            // set the owning side to null (unless already changed)
            if ($invoice->getAppointment() === $this) {
                $invoice->setAppointment(null);
            }
        }

        return $this;
    }

    //Do not forget to catch the exception in the controller
    public function validate(ValidatorInterface $validator){
        $errors = $validator->validate($this);
        //Check if instance of ConstraintViolationList
        dump($errors);
        if (count($errors) > 0) {
            $errorsString = (string) $errors;
            throw new \Exception($errorsString);
        }
    }

    public function getPhoneCountryCode(): ?string
    {
        return $this->phoneCountryCode;
    }

    public function setPhoneCountryCode(string $phoneCountryCode): static
    {
        $this->phoneCountryCode = $phoneCountryCode;

        return $this;
    }

    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(?string $phoneNumber): static
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): static
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): static
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getDurationMinutes(): ?int
    {
        return $this->duration_minutes;
    }

    public function setDurationMinutes(int $duration_minutes): static
    {
        $this->duration_minutes = $duration_minutes;

        return $this;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

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
}
