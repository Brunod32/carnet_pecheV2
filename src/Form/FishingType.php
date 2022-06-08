<?php

namespace App\Form;

use App\Entity\Fishing;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class FishingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('fishRace', TextType::class)
            ->add('date', DateType::class, [
                'widget' => 'choice',
                'format' => 'd-M-y',
            ])
            ->add('place', TextType::class)
            ->add('lure', TextType::class)
            ->add('weather', TextType::class)
            ->add('bait', TextType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Fishing::class,
        ]);
    }
}
