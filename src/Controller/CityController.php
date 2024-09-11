<?php

namespace App\Controller;

use App\Entity\City;
use App\Repository\CityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
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

    #[Route('/city/add', name: 'app_city_add')]
    public function add(EntityManagerInterface $manager, Request $request): Response
    {
        $city = new City();

        var_dump($request->request->all());
        $cityName = $request->request->get('cityName');
        $zipCode = $request->request->get('zipCode');
        $placeName = $request->request->get('placeName');
        $streetName = $request->request->get('streetName');


        $city->setCityName($cityName);
        $city->setZipCode($zipCode);
        $city->setPlaceName($placeName);
        $city->setStreetName($streetName);

        $manager->persist($city);
        $manager->flush();

        return $this->redirectToRoute('app_city');
    }

    #[Route('/city/{id}', name: 'app_get_one_city')]
    public function getOne(CityRepository $cityRepository, EntityManagerInterface $manager, int $id): Response
    {
        $city = $cityRepository->find($id);
        return $this->json($city, 200, [], ['groups' => 'city:read']);
    }

    #[Route('/city/edit/{id}', name: 'app_city_edit')]
    public function edit(CityRepository $cityRepository, EntityManagerInterface $manager, Request $request, int $id): Response
    {
        $cityToEdit = $cityRepository->find($id);

        $cityName = $request->request->get('cityName');
        $zipCode = $request->request->get('zipCode');
        $placeName = $request->request->get('placeName');
        $streetName = $request->request->get('streetName');

        $cityToEdit->setCityName($cityName);
        $cityToEdit->setZipCode($zipCode);
        $cityToEdit->setPlaceName($placeName);
        $cityToEdit->setStreetName($streetName);

        $manager->persist($cityToEdit);
        $manager->flush();

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
