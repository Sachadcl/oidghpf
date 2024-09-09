<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\AddUserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin', name: 'admin_')]
#[IsGranted('ROLE_ADMIN')]
class AdminController extends AbstractController
{

    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    #[Route('/user-management', name: 'user-management')]
    public function home(): Response
    {
        $users = $this->userRepository->findAll();

        return $this->render('admin/user-management.html.twig', [
            'users' => $users,
        ]);
    }

    #[Route('/add', name: 'add')]
    public function add(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $userPasswordHasher): Response
    {
        $form = $this->createForm(AddUserType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();

            $profilePicture = "https://via.placeholder.com/640x480.png/004455?text=" . $this->generateRandomLetters();
            $user->setProfilePicture($profilePicture);

            $password = $form->get('password')->getData();
            $user->setPassword($userPasswordHasher->hashPassword($user, $password));

            $user->setRoles(['ROLE_USER']);
            $user->setIsActive(true);

            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', 'Utilisateur créé avec succès.');
            return $this->redirectToRoute('admin_user-management');
        }
        return $this->render('admin/add_user.html.twig', ['userForm' => $form->createView(),]);
    }

    #[Route('/delete/{id}', name: 'delete')]
    public function delete(User $user, EntityManagerInterface $entityManager): Response
    {
        if (in_array("ROLE_ADMIN", $user->getRoles())) {
            return $this->redirectToRoute('admin_user-management');
        }

        $entityManager->remove($user);
        $entityManager->flush();

        return $this->redirectToRoute('admin_user-management');
    }

    #[Route('/activate/{id}', name: 'activate')]
    public function activate(User $user, EntityManagerInterface $entityManager): Response
    {
        $user->setIsActive(true);
        $entityManager->flush();

        return $this->redirectToRoute('admin_user-management');
    }

    #[Route('/deactivate/{id}', name: 'deactivate')]
    public function deactivate(User $user, EntityManagerInterface $entityManager): Response
    {
        $user->setIsActive(false);
        $entityManager->flush();

        return $this->redirectToRoute('admin_user-management');
    }

    function generateRandomLetters($length = 4)
    {
        $letters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        return substr(str_shuffle($letters), 0, $length);
    }
}