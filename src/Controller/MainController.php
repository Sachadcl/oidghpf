<?php

namespace App\Controller;

use App\Repository\CampusRepository;
use App\Repository\OutingRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/', name: 'main_')]
class MainController extends AbstractController
{

    #[Route('/', name: 'home')]
    public function home(CampusRepository $campusRepository, OutingRepository $outingRepository): Response
    {
        $campuses = $campusRepository->findAll();
        $outings = $outingRepository->findAll();

        return $this->render('home.html.twig', [
            'campuses' => $campuses,
            'outings' => $outings
        ]);
    }
}
