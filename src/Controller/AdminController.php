<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\AddUserType;
use App\Form\ImportCsvType;
use App\Repository\CampusRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Validator\Validator\ValidatorInterface;

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

    #[Route('/import-csv', name: 'import-csv')]
    public function importCsv(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $userPasswordHasher, CampusRepository $campusRepository, ValidatorInterface $validator): Response
    {
        $form = $this->createForm(ImportCsvType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form->get('csvFile')->getData();

            if (($handle = fopen($file->getPathname(), "r")) !== false) {
                while (($data = fgetcsv($handle)) !== false) {
                    $user = new User();

                    $user->setIdCampus($campusRepository->find($data[0]));
                    $user->setEmail($data[1]);
                    $user->setUsername($data[2]);
                    $user->setLastName($data[3]);
                    $user->setFirstName($data[4]);
                    $user->setTelephone($data[5]);
                    $user->setPassword($userPasswordHasher->hashPassword($user, $data[6]));
                    $user->setRoles([$data[7]]);
                    $user->setIsActive(filter_var($data[8]));

                    $profilePicture = "https://via.placeholder.com/640x480.png/004455?text=" . $this->generateRandomLetters();
                    $user->setProfilePicture($profilePicture);

                    $errors = $validator->validate($user);
                    if (count($errors) > 0) {
                        return $this->render('admin/import-csv.html.twig', [
                            'importForm' => $form->createView(),
                            'error' => "Votre fichier ou ses données sont invalides."
                        ]);
                    }

                    $entityManager->persist($user);
                }

                fclose($handle);
                $entityManager->flush();
            }

            $this->addFlash('success', 'Utilisateur importés avec succès.');
            return $this->redirectToRoute('admin_user-management');
        }

        return $this->render('admin/import-csv.html.twig', ['importForm' => $form->createView(), 'error' => null]);
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