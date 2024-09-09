<?php

namespace App\Controller;


use App\Class\Filter;

use App\Repository\CampusRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\OutingRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/', name: 'main_')]
class MainController extends AbstractController
{

    #[Route('/', name: 'home')]
    public function home(OutingRepository $outingRepository, CampusRepository $campusRepository): Response
    {
        $signedOutings = $outingRepository->findAll();
        return $this->render('home.html.twig', [
            'outings' => $outingRepository->findAll(),
            'campuses' => $campusRepository->findAll(),
        ]);
    }

    #[Route('/search', name: 'search')]
    public function search(Request $request, UserInterface $user, OutingRepository $outingRepository, CampusRepository $campusRepository, UserRepository $userRepository): Response
    {

        $startDate = new \DateTime($request->query->get('start'));
        $endDate = new \DateTime($request->query->get('end'));

        $filter = new Filter();

        $filter->setName($request->query->get('name'));
        $filter->setBeginDate($startDate);
        $filter->setEndDate($endDate);
        $filter->setCampusId($request->query->get('selectedCampus'));


        $user = $userRepository->getByEmail($user->getUserIdentifier());

        if ($user && $request->query->get('isOrganizer') !== null) {
            $filter->setIsOrganizer($user->getId());
        } else {
            $filter->setIsOrganizer(null);
        }

        if ($user && $request->query->get('isParticipant') !== null) {
            $filter->setIsParticipant($user->getId());
        } else {
            $filter->setIsParticipant(null);
        }

        if ($user && $request->query->get('notParticipant') !== null) {
            $filter->setNotParticipant($user->getId());
        } else {
            $filter->setNotParticipant(null);
        }

        $filter->setFinishedOutings($request->query->get('finishedOutings') !== null);

        $outings = $outingRepository->search($filter);
        return $this->render('home.html.twig', [
            'outings' => $outings,
            'campuses' => $campusRepository->findAll(),
        ]);
    }

    #[Route('/filter', name: 'filter')]
    public function filter(Request $request, SerializerInterface $serializerInterface, OutingRepository $outingRepository, CampusRepository $campusRepository, Security $security): Response
    {


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
