<?php

namespace App\Form\Type;

use App\Form\DataTransformer\DatetimeUtcToTimezoneTransformer;
use App\Entity\Appointment;
use App\Entity\Business;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


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
        $business = $this->entityManager->getRepository(Business::class)->find(2);
        //Simulate that the business has a timezone of 'America/New_York'
        dump($business);
        $timezone = $business ? $business->getTimezoneName() : 'UTC';
        dump($timezone);
        // exit;
        $builder->add('firstName', TextType::class, ['required' => false]);
        $builder->add('lastName', TextType::class, ['required' => true]);
        $builder->add('phoneNumber', TextType::class, ['required' => false]);
        // Get datetime in the user's timezone
        // Show according to the user's timezone
        //Use data transformer to convert the datetime to the user's timezone
        $builder->add('startDateTimeUtc', DateTimeType::class, [
            'widget' => 'choice',
            'input' => 'datetime_immutable',
        ]);
        $builder->get('startDateTimeUtc')->addModelTransformer(new DatetimeUtcToTimezoneTransformer($timezone));
        $builder->add('appointmentServices');
        $builder->add('save', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Appointment::class,
            'business_id' => null,
        ]);
    }
}