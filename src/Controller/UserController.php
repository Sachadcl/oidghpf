<?php

namespace App\Controller;

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

            if ($user->getPlainPassword()) {
                $user->setPassword(
                    $userPasswordHasher->hashPassword(
                        $user,
                        $form->get('plainPassword')->getData()
                    )
                );
            }

            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', 'Votre profil a été mis à jour avec succès.');
            return $this->redirectToRoute('/{id}', ['id' => $user->getId()]);
        }

        return $this->render('user/settings.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
