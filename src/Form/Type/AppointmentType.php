<?php

namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\Form\FormBuilderInterface;


class AppointmentType extends AbstractType{
    public function buildForm(FormBuilderInterface $builder, array $options){
        $builder->add('phoneNumber');
    }
}