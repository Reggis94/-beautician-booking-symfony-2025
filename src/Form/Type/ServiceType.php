<?php

namespace App\Form\Type;

use App\Entity\Service;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ServiceType extends AbstractType{
    public function buildForm(FormBuilderInterface $builder, array $options){
        $builder
            ->add('name', TextType::class)
            ->add('duration', TimeType::class, ['widget' => 'choice', 'placeholder' => [
        'hour' => 'Hour', 'minute' => 'Minute'
    ],])
            ->add('price', MoneyType::class, ['currency' => 'USD', 'divisor' => 100])
            ->add('description');
    }

    public function configureOptions(OptionsResolver $resolver){
        $resolver->setDefaults([
            'data_class' => Service::class]);
        }

}