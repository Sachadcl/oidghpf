<?php

namespace App\Form;

use App\Entity\Campus;
use App\Entity\Outing;
use App\Entity\Users;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username')
            ->add('last_name')
            ->add('first_name')
            ->add('email')
            ->add('telephone')
            ->add('password')
            ->add('profile_picture')
            ->add('role')
            ->add('id_campus', EntityType::class, [
                'class' => Campus::class,
                'choice_label' => 'id',
            ])
            ->add('outings', EntityType::class, [
                'class' => Outing::class,
                'choice_label' => 'id',
                'multiple' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Users::class,
        ]);
    }
}
