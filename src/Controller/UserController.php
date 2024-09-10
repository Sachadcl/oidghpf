<?php

namespace App\Controller;

use App\Form\UserType;
use App\Repository\UserRepository;
use App\Service\FileUploader;
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
    private FileUploader $fileUploader;

    public function __construct(UserRepository $userRepository, FileUploader $fileUploader)
    {
        $this->userRepository = $userRepository;
        $this->fileUploader = $fileUploader;
    }

    #[Route('/card/{id}', name: 'profile')]
    public function profile(int $id): Response
    {
        $user = $this->userRepository->find($id);

        return $this->render('user/user_card.html.twig', array(
            'user' => $user
        ));
    }

    #[Route('/planner/{email}', name: 'planner_profile')]
    public function plannerProfile(string $email): Response
    {
        $user = $this->userRepository->getByEmail($email);

        return $this->json($user, 200, [], ['groups' => 'user:read']);
    }

    #[Route('/settings', name: 'settings')]
    public function settings(
        Request $request,
        EntityManagerInterface $entityManager,
        Security $security,
        UserPasswordHasherInterface $userPasswordHasher
    ): Response {

        $user = $security->getUser();

        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $user = $form->getData();

            $plainPassword = $form->get('new_password')->getData();
            if ($plainPassword) {
                $newPassword = $form->get('new_password')->getData();
                $user->setPassword($userPasswordHasher->hashPassword($user, $newPassword));
            }

            $file = $form->get('profile_picture')->getData();
            if ($file) {
                $newFileName = $this->fileUploader->upload($file);
                $user->setProfilePicture($newFileName);
            }

            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', 'Votre profil a été mis à jour avec succès.');
            return $this->redirectToRoute('main_home');
        }
        return $this->render('user/profile_management.html.twig', ['userForm' => $form->createView(),]);
    }
}
