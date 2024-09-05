<?php

namespace App\Form;

use App\Repository\UserRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class ForgotPasswordType extends AbstractType
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => 'Votre adresse mail :',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Votre adresse mail ne doit pas être vide.',
                    ]),
                    new Callback([$this, 'validateEmail']),
                ],
            ]);
    }

    public function validateEmail($value, ExecutionContextInterface $context): void
    {
        if ($value && !$this->userRepository->findOneBy(['email' => $value])) {
            $context->buildViolation('Aucun compte avec cette adresse mail n\'existe !')
                ->addViolation();
        }
    }


    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([]);
    }
}
