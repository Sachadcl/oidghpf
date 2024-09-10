<?php

namespace App\Controller;

use App\Repository\CampusRepository;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CampusController  extends AbstractController
{
    #[Route('/campus', name: 'app_campus')]
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
            $this->addFlash('error', 'Impossible de supprimer cette ville');
        }


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
