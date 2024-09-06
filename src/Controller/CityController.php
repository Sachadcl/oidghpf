<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class CityController  extends AbstractController
{
    #[Route('/city', name: 'app_city')]
    #[IsGranted('ROLE_ADMIN')]
    public function index(): Response
    {
        $this->denyAccessUnlessGranted("ROLE_ADMIN");

        return $this->render('city/index.html.twig', [
            'controller_name' => 'CityController',
        ]);
    }
}
