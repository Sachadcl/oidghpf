<?php

namespace App\Controller;

use App\Entity\Campus;
use App\Repository\CampusRepository;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class CampusController  extends AbstractController
{
    #[Route('/campus', name: 'app_campus')]
    #[IsGranted('ROLE_ADMIN')]
    public function index(CampusRepository $campusRepository): Response
    {
        $campus = $campusRepository->findAll();
        return $this->render('campus/index.html.twig', [
            'campus' => $campus,
        ]);
    }

    #[Route('/campus/delete/{id}', name: 'app_campus_delete')]
    public function delete(CampusRepository $campusRepository, EntityManagerInterface $manager, int $id): Response
    {
        $campusToDelete = $campusRepository->find($id);
        try {
            $manager->remove($campusToDelete);
            $manager->flush();
        } catch (Exception $error) {
            $this->addFlash('error', 'Impossible de supprimer ce campus');
        }


        return $this->redirectToRoute('app_campus');
    }

    #[Route('/campus/add/', name: 'app_campus_add')]
    public function add(EntityManagerInterface $manager, Request $request): Response
    {
        $campus = new Campus();


        $campusName = $request->request->get('campusName');

        $campus->setCampusName($campusName);

        $manager->persist($campus);
        $manager->flush();

        return $this->redirectToRoute('app_campus');
    }

    #[Route('/campus/{id}', name: 'app_campus_get_one')]
    public function getOne(EntityManagerInterface $manager, CampusRepository $campusRepository, Request $request): Response
    {
        $campusToEdit = $campusRepository->find($request->get('id'));
        return $this->json($campusToEdit, 200, [], ['groups' => 'campus:read']);
    }

    #[Route('/campus/edit/{id}', name: 'app_campus_edit')]
    public function edit(EntityManagerInterface $manager, CampusRepository $campusRepository, Request $request): Response
    {
        $campusToEdit = $campusRepository->find($request->get('id'));


        $campusName = $request->request->get('campusName');



        $campusToEdit->setCampusName($campusName);

        $manager->persist($campusToEdit);
        $manager->flush();

        return $this->redirectToRoute('app_campus');
    }

    #[Route('/campus/search', name: 'app_campus_search')]
    public function search(CampusRepository $campusRepository, EntityManagerInterface $manager, Request $request): Response
    {
        $search = $request->query->get('q');
        $campus = $campusRepository->search($search);

        return $this->render('campus/index.html.twig', [
            'campus' => $campus,
        ]);
    }
}
