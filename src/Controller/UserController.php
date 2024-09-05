<?php

namespace App\Controller;

use App\Form\ForgotPasswordType;
use App\Form\ResetPasswordType;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/user', name: 'user_')]
class UserController extends AbstractController
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    #[Route('/card/{id}', name: 'profile')]
    public function profile(int $id): Response
    {
        $user = $this->userRepository->find($id);

        return $this->render('user/user_card.html.twig', array(
            'user' => $user
        ));
    }

    #[Route('/settings', name: 'settings')]
    public function settings(Request $request, EntityManagerInterface $entityManager, Security $security, UserPasswordHasherInterface $userPasswordHasher): Response
    {
        $user = $security->getUser();

        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $user = $form->getData();

            $plainPassword = $form->get('new_password')->getData();
            if ($plainPassword) {

                $newPassword = $form->get('plainPassword')->getData();
                $user->setPassword($userPasswordHasher->hashPassword($user, $newPassword));
            }

            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', 'Votre profil a été mis à jour avec succès.');
            return $this->redirectToRoute('main_home');
        }
        dump($form);
        return $this->render('user/profile_management.html.twig', ['userForm' => $form->createView(),]);
    }

    #[Route('/forgot-password', name: 'forgot_password')]
    public function forgotPassword(Request $request): Response
    {
        $form = $this->createForm(ForgotPasswordType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($this->userRepository->findOneBy(['email' => $form->get('email')->getData()])) {
                $this->addFlash('success', 'Si l\'adresse mail existe, un lien vous sera envoyé. (faut imaginer)');
                return $this->redirectToRoute('user_reset_password', ['email' => $form->get('email')->getData()]);
            }
        }

        return $this->render('user/forgot_password.html.twig', [
            'forgotPasswordForm' => $form->createView(),
        ]);
    }

    #[Route('/reset-password', name: 'reset_password')]
    public function resetPassword(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $userPasswordHasher): Response
    {
        $email = $request->query->get('email');

        $form = $this->createForm(ResetPasswordType::class, [
            'email' => $email,
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $email = $form->get('email')->getData();
            $user = $this->userRepository->findOneBy(['email' => $email]);

            if ($user) {
                $newPassword = $form->get('new_password')->getData();
                $user->setPassword($userPasswordHasher->hashPassword($user, $newPassword));

                $entityManager->persist($user);
                $entityManager->flush();

                $this->addFlash('success', 'Password reset successfully.');
                return $this->redirectToRoute('app_login');
            }

            $this->addFlash('error', 'No user found with that email.');
        }

        return $this->render('user/reset_password.html.twig', [
            'resetPasswordForm' => $form->createView(),
        ]);
    }
}
