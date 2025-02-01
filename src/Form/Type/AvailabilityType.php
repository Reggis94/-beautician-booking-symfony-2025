<?php

namespace App\Form\Type;

use App\Entity\Day;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class AvailabilityType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('day', EntityType::class, [
            'class' => Day::class,
            'choice_label' => 'name',
            'multiple' => true,
            'expanded' => true,
        ]);
        $builder->add('startTime', TimeType::class, [
            'widget' => 'choice',
            'input' => 'datetime_immutable',
        ]);
        $builder->add('endTime', TimeType::class, [
            'widget' => 'choice',
            'input' => 'datetime_immutable',
        ]);
        $builder->add('intervalMinutes', IntegerType::class, ['label' => 'Minimum interval in minutes between two slots (minutes)', 'required' => false]);
        $builder->add('save', SubmitType::class, ['label' => 'Add new availability']);
    }
}