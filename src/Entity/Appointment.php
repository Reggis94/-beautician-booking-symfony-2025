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
}
