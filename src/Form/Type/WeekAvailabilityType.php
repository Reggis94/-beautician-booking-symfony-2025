<?php

namespace App\Form\Type;

use App\Entity\Day;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class WeekAvailabilityType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('startTimeMonday', TimeType::class, [
            'widget' => 'choice',
            'input' => 'datetime_immutable',
        ]);
        $builder->add('endTimeMonday', TimeType::class, [
            'widget' => 'choice',
            'input' => 'datetime_immutable',
        ]);
        $builder->add('intervalMinutesMonday', IntegerType::class, ['label' => 'Minimum interval in minutes between two slots (minutes)', 'required' => false,  'empty_data' => 5, 'data' => 5]);
        $builder->add('startTimeTuesday', TimeType::class, [
            'widget' => 'choice',
            'input' => 'datetime_immutable',
        ]);
        $builder->add('endTimeTuesday', TimeType::class, [
            'widget' => 'choice',
            'input' => 'datetime_immutable',
        ]);
        $builder->add('intervalMinutesTuesday', IntegerType::class, ['label' => 'Minimum interval in minutes between two slots (minutes)', 'required' => false,  'empty_data' => 5, 'data' => 5]);
        $builder->add('startTimeWednesday', TimeType::class, [
            'widget' => 'choice',
            'input' => 'datetime_immutable',
        ]);
        $builder->add('endTimeWednesday', TimeType::class, [
            'widget' => 'choice',
            'input' => 'datetime_immutable',
        ]);
        $builder->add('intervalMinutesWednesday', IntegerType::class, ['label' => 'Minimum interval in minutes between two slots (minutes)', 'required' => false,  'empty_data' => 5, 'data' => 5]);
        $builder->add('startTimeThursday', TimeType::class, [
            'widget' => 'choice',
            'input' => 'datetime_immutable',
        ]);
        $builder->add('endTimeThursday', TimeType::class, [
            'widget' => 'choice',
            'input' => 'datetime_immutable',
        ]);
        $builder->add('intervalMinutesThursday', IntegerType::class, ['label' => 'Minimum interval in minutes between two slots (minutes)', 'required' => false,  'empty_data' => 5, 'data' => 5]);
        $builder->add('startTimeFriday', TimeType::class, [
            'widget' => 'choice',
            'input' => 'datetime_immutable',
        ]);
        $builder->add('endTimeFriday', TimeType::class, [
            'widget' => 'choice',
            'input' => 'datetime_immutable',
        ]);
        $builder->add('intervalMinutesFriday', IntegerType::class, ['label' => 'Minimum interval in minutes between two slots (minutes)', 'required' => false,  'empty_data' => 5, 'data' => 5]);
        $builder->add('startTimeSaturday', TimeType::class, [
            'widget' => 'choice',
            'input' => 'datetime_immutable',
        ]);
        $builder->add('endTimeSaturday', TimeType::class, [
            'widget' => 'choice',
            'input' => 'datetime_immutable',
        ]);
        $builder->add('intervalMinutesSaturday', IntegerType::class, ['label' => 'Minimum interval in minutes between two slots (minutes)', 'required' => false,  'empty_data' => 5, 'data' => 5]);
        $builder->add('startTimeSunday', TimeType::class, [
            'widget' => 'choice',
            'input' => 'datetime_immutable',
        ]);
        $builder->add('startTimeSunday', TimeType::class, [
            'widget' => 'choice',
            'input' => 'datetime_immutable',
        ]);
        $builder->add('endTimeSunday', TimeType::class, [
            'widget' => 'choice',
            'input' => 'datetime_immutable',
        ]);
        $builder->add('intervalMinutesSunday', IntegerType::class, ['label' => 'Minimum interval in minutes between two slots (minutes)', 'required' => false,  'empty_data' => 5, 'data' => 5]);
        $builder->add('save', SubmitType::class, ['label' => 'Add new availability']);
    }
}