<?php

namespace App\Form;

use App\Entity\Campus;
use App\Entity\City;
use App\Entity\Outing;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OutingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('outing_name')
            ->add('outing_date', null, [
                'widget' => 'single_text',
            ])
            ->add('registration_deadline', null, [
                'widget' => 'single_text',
            ])
            ->add('slots')

            ->add('id_campus', EntityType::class, [
                'class' => Campus::class,
                'choice_label' => 'campus_name',
            ])
            ->add('id_city', EntityType::class, [
                'class' => City::class,
                'choice_label' => 'place_name',
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description de la sortie',
                'attr' => ['rows' => 3],
            ])




        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Outing::class,
        ]);
    }
}
