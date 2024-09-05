<?php

namespace App\Controller;

use App\Repository\CampusRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\OutingRepository;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

#[Route('/', name: 'main_')]
class MainController extends AbstractController
{

    #[Route('/', name: 'home')]
    public function home(OutingRepository $outingRepository, CampusRepository $campusRepository): Response
    {
        return $this->render('home.html.twig', [
            'outings' => $outingRepository->findAll(),
            'campuses' => $campusRepository->findAll(),
        ]);
    }

    #[Route('/search', name: 'search')]
    public function search(Request $request, OutingRepository $outingRepository, CampusRepository $campusRepository): Response
    {
        $name = $request->query->get('name');
        $beginDate = $request->query->get('beginDate');
        $endDate = $request->query->get('endDate');
        $campusId = $request->query->get('campusId');

        $beginDate = $beginDate ? new \DateTime($beginDate) : null;
        $endDate = $endDate ? new \DateTime($endDate) : null;

        $outings = $outingRepository->search($name, $beginDate, $endDate, $campusId);

        return $this->render('home.html.twig', [
            'outings' => $outings,
            'campuses' => $campusRepository->findAll(),
        ]);
    }

    #[Route('/filter', name: 'filter')]
    public function filter(Request $request, OutingRepository $outingRepository, CampusRepository $campusRepository, Security $security): Response
    {
        $data = json_decode($request->getContent(), true);
        if (isset($data['deadline']) && $data['deadline'] == 'true') {
            $allOutings = $outingRepository->findAll();

            $outings = array_filter($allOutings, function ($outing) {
                return $outing->getOutingDate() < new \DateTime();
            });


            $html = $this->renderView('outing/_list.html.twig', [
                'outings' => $outings,
                'campuses' => $campusRepository->findAll(),
            ]);


            return new JsonResponse(['html' => $html]);
        } else if (isset($data['organizer']) && $data['organizer'] == 'true') {
            return $this->redirectToRoute('main_home');
        } else if (isset($data['participant']) && $data['participant'] == 'true') {
            return $this->redirectToRoute('main_home');
        } else if (isset($data['notSignedIn']) && $data['notSignedIn'] == 'true') {
            $user = $security->getUser();

            // $members = $outingRepository->find($outings->getIdMember());

            $html = $this->renderView('outing/_list.html.twig', [
                'outings' => $outingRepository->findAll(),
                'campuses' => $campusRepository->findAll(),
            ]);
        }

        $isOrganizer = $outingRepository->outingWhereIAmOrganizer(1, 1);


        return $this->json(['error' => 'Invalid Request'], 400);
    }
}
