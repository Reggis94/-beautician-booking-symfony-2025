<?php

namespace App\Form\Type;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

class AppointmentType extends AbstractType
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // Assuming you have a way to get the current business ID
        $businessId = $options['business_id'];
        $business = $this->entityManager->getRepository(Business::class)->find($businessId);
        $timezone = $business ? $business->getTimezone() : 'UTC';

        $builder->add('phoneNumber');
        // Get datetime in the user's timezone
        // Show according to the user's timezone
        $builder->add('startDateTimeUtc', DateTimeType::class, [
            'widget' => 'choice',
            'input' => 'datetime_immutable',
            'timezone' => $timezone,
        ]);
        $builder->add('appointmentServices');
        $builder->add('save', SubmitType::class);
    }
}