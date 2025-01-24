<?php

namespace App\Entity;

use App\Repository\InvoiceRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: InvoiceRepository::class)]
class Invoice
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'invoices')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Appointment $appointment = null;

    #[ORM\Column]
    private ?float $clientBookingFees = null;

    #[ORM\Column(nullable: true)]
    private ?float $paymentProcessingFees = null;

    #[ORM\Column]
    private ?float $price = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAppointment(): ?Appointment
    {
        return $this->appointment;
    }

    public function setAppointment(?Appointment $appointment): static
    {
        $this->appointment = $appointment;

        return $this;
    }

    public function getClientBookingFees(): ?float
    {
        return $this->clientBookingFees;
    }

    public function setClientBookingFees(float $clientBookingFees): static
    {
        $this->clientBookingFees = $clientBookingFees;

        return $this;
    }

    public function getPaymentProcessingFees(): ?float
    {
        return $this->paymentProcessingFees;
    }

    public function setPaymentProcessingFees(?float $paymentProcessingFees): static
    {
        $this->paymentProcessingFees = $paymentProcessingFees;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): static
    {
        $this->price = $price;

        return $this;
    }
}
