<?php

namespace App\Form;

use App\Entity\Campus;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\EqualTo;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class UserType extends AbstractType
{
    private $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username', null, [
                'label' => 'Pseudonyme :',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Votre nom d\'utilisateur ne doit pas être vide.',
                    ]),
                ],
            ])
            ->add('last_name', null, [
                'label' => 'Prénom :',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Votre nom ne doit pas être vide.',
                    ]),
                ],
            ])
            ->add('first_name', null, [
                'label' => 'Nom :',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Votre prénom ne doit pas être vide.',
                    ]),
                ],
            ])
            ->add('email', null, [
                'label' => 'email :',
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
                'label' => 'Téléphone :',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Votre numéro de téléphone ne doit pas être vide.',
                    ]),
                ],
            ])
            ->add('new_password', PasswordType::class, [
                'label' => 'Nouveau mot de passe :',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Votre mot de passe doit contenir au moins {{ limit }} caractères.',
                    ]),
                ],
            ])
            ->add('new_confirmation', PasswordType::class, [
                'label' => 'Confirmation nouveau mot de passe :',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new EqualTo([
                        'propertyPath' => 'parent.all[new_password].data',
                        'message' => 'Les mots de passe doivent correspondre.',
                    ]),
                ],
            ])
            ->add('id_campus', EntityType::class, [
                'label' => 'Campus :',
                'class' => Campus::class,
                'choice_label' => 'campus_name',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez sélectionner un campus.',
                    ]),
                ],
            ])
            ->add('profile_picture', null, [
                'label' => 'Changer votre Photo de profil :',
                'attr' => [
                    'placeholder' => 'https://via.placeholder.com/640x480.png/004455?text=iure',
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Votre nom ne doit pas être vide.',
                    ]),
                ],
            ])
            ->add('current_password', PasswordType::class, [
                'label' => 'Mot de passe actuel :',
                'mapped' => false,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Votre mot de passe actuel ne doit pas être vide.',
                    ]),
                    new Callback([$this, 'validateCurrentPassword']),
                ],
            ])
        ;
    }

    public function validateCurrentPassword($value, ExecutionContextInterface $context): void
    {
        $user = $context->getRoot()->getData();
        $currentPasswordIsValid = $this->passwordHasher->isPasswordValid($user, $value);

        if (!$currentPasswordIsValid) {
            $context->buildViolation('Le mot de passe actuel est incorrect.')
                ->addViolation();
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
