<?php

namespace App\Controller;

use App\Form\ForgotPasswordType;
use App\Form\ResetPasswordType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use LogicException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{

    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    #[Route('/forgot-password', name: 'forgot_password')]
    public function forgotPassword(Request $request): Response
    {
        $form = $this->createForm(ForgotPasswordType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($this->userRepository->findOneBy(['email' => $form->get('email')->getData()])) {
                $this->addFlash('success', 'Si l\'adresse mail existe, un lien vous sera envoyé. (faut imaginer)');
                return $this->redirectToRoute('reset_password', ['email' => $form->get('email')->getData()]);
            }
        }

        return $this->render('security/forgot_password.html.twig', [
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
        }

        return $this->render('security/reset_password.html.twig', [
            'resetPasswordForm' => $form->createView(),
        ]);
    }
}
