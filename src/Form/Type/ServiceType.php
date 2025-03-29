<?php

namespace App\Form\Type;

use App\Entity\Service;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ServiceType extends AbstractType{
    public function buildForm(FormBuilderInterface $builder, array $options){
        $categories = $options['entity_manager']->getRepository(Category::class)->findBy(['business' => $options['business'], 'deletedAt' => null]);


        $builder
            ->add('name', TextType::class)
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'name',
                'placeholder' => 'Select a category',
                'choices' => $categories
            ])
            ->add('durationMinute', TimeType::class, ['widget' => 'choice', 'placeholder' => [
        'hour' => 'Hour', 'minute' => 'Minute'
    ],])
            ->add('price', MoneyType::class, ['currency' => 'USD', 'divisor' => 100, 'input' => 'integer'])
            ->add('description')
            ->add('isActive', ChoiceType::class, ['label' => 'Display service on booking site to clients?', 'choices' => ['Make this service bookable' => true, 'Hide this service for now from your client' => false]])
            ->add('submit', SubmitType::class)
            ->get('durationMinute')->addModelTransformer(new CallbackTransformer(
                function($durationAsInt){
                    $hours = floor($durationAsInt / 60);
                    $minutes = $durationAsInt % 60;
                    return (new \DateTime())->setTime($hours, $minutes);
                },
                function($durationAsDateTime){
                    //TODO: invalid message if duration is not in range
                    return ($durationAsDateTime->format('H') * 60) + $durationAsDateTime->format('i');
                }
            ));
            
    }

    public function configureOptions(OptionsResolver $resolver){
        $resolver->setDefaults([
            'data_class' => Service::class]);
        }

}