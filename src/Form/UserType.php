<?php

namespace App\Form;

use App\Entity\Campus;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\EqualTo;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Email;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username', null, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Votre nom d\'utilisateur ne doit pas être vide.',
                    ]),
                ],
            ])
            ->add('last_name', null, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Votre nom ne doit pas être vide.',
                    ]),
                ],
            ])
            ->add('first_name', null, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Votre prénom ne doit pas être vide.',
                    ]),
                ],
            ])
            ->add('email', null, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Votre adresse e-mail ne doit pas être vide.',
                    ]),
                    new Email([
                        'message' => 'Votre e-mail n\'est pas valide.',
                    ]),
                ],
            ])
            ->add('telephone', null, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Votre numéro de téléphone ne doit pas être vide.',
                    ]),
                ],
            ])
            ->add('password', PasswordType::class, [
                'mapped' => false,
                'attr' => ['autocomplete' => 'new-password'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez saisir un mot de passe.',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Votre mot de passe doit contenir au moins {{ limit }} caractères.',
                        'max' => 4096,                                                                                  // longueur maximale autorisée par Symfony pour des raisons de sécurité
                    ]),
                ],
            ])
            ->add('confirmation', PasswordType::class, [
                'mapped' => false,
                'attr' => ['autocomplete' => 'new-password'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez confirmer votre mot de passe.',
                    ]),
                    new EqualTo([
                        'propertyPath' => 'parent.all[password].data',
                        'message' => 'Les mots de passe doivent correspondre.',
                    ]),
                ],
            ])
            ->add('id_campus', EntityType::class, [
                'class' => Campus::class,
                'choice_label' => 'id',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez sélectionner un campus.',
                    ]),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
