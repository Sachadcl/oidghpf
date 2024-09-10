<?php

namespace App\Controller;

use App\Repository\CityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use PDOException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class CityController  extends AbstractController
{
    #[Route('/city', name: 'app_city')]
    #[IsGranted('ROLE_ADMIN')]
    public function index(CityRepository $cityRepository): Response
    {
        $this->denyAccessUnlessGranted("ROLE_ADMIN");

        $cities = $cityRepository->findAll();
        return $this->render('city/index.html.twig', [
            'cities' => $cities,
        ]);
    }

    #[Route('/city/delete/{id}', name: 'app_city_delete')]
    public function delete(CityRepository $cityRepository, EntityManagerInterface $manager, int $id): Response
    {
        $cityToDelete = $cityRepository->find($id);
        try {
            $manager->remove($cityToDelete);
            $manager->flush();
        } catch (Exception $error) {
            $this->addFlash('error', 'Impossible de supprimer cette ville');
        }


        return $this->redirectToRoute('app_city');
    }

    #[Route('/city/search', name: 'app_city_search')]
    public function search(CityRepository $cityRepository, EntityManagerInterface $manager, Request $request): Response
    {
        $search = $request->query->get('q');
        $cities = $cityRepository->search($search);



        return $this->render('city/index.html.twig', [
            'cities' => $cities,
        ]);
    }
}
