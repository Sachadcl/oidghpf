<?php

namespace App\Controller;

use App\Entity\Outing;
use App\Form\OutingType;
use App\Repository\OutingRepository;
use App\Service\OutingService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/outing')]
final class OutingController extends AbstractController
{
    #[Route(name: 'app_outing_index', methods: ['GET'])]
    public function index(OutingRepository $outingRepository): Response
    {
        return $this->render('outing/index.html.twig', [
            'outings' => $outingRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_outing_new', methods: ['GET', 'POST'])]
    public function new(Request $request, OutingService $outingService, EntityManagerInterface $entityManager): Response
    {
        $outing = new Outing();
        $form = $this->createForm(OutingType::class, $outing);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $newOutingState = $outingService->calculateOutingState($outing);
            $outing->setState($newOutingState);
            $entityManager->persist($outing);
            $entityManager->flush();

            return $this->redirectToRoute('main_home', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('outing/new.html.twig', [
            "is_editable" => false,
            'outing' => $outing,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_outing_show', methods: ['GET'])]
    public function show(Outing $outing): Response
    {
        return $this->render('outing/show.html.twig', [
            'outing' => $outing,
        ]);
    }

    #[Route('/ajax/{id}', name: 'app_outing_show_ajax', methods: ['GET'])]
    public function showwithModal(Outing $outing): Response
    {
        return $this->json($outing, 200, [], ['groups' => 'outing:read']);
    }

    #[Route('/{id}/edit', name: 'app_outing_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Outing $outing, OutingService $outingService , EntityManagerInterface $entityManager, Security $security): Response
    {
        $form = $this->createForm(OutingType::class, $outing);
        $form->handleRequest($request);

        if ($outing->getIdOrganizer()->getId() != $security->getUser()->getId()) {
            return $this->redirectToRoute('main_home');
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $newOutingState = $outingService->calculateOutingState($outing);
            $outing->setState($newOutingState);
            $entityManager->flush();

            return $this->redirectToRoute('main_home', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('outing/edit.html.twig', [
            "is_editable" => true,
            'outing' => $outing,
            'form' => $form,
        ]);
    }

    #[Route('/publish/{id}', name: 'app_outing_publish')]
    public function publish(Outing $outing, EntityManagerInterface $entityManager, Security $security): Response
    {
        if ($outing->getIdOrganizer()->getId() != $security->getUser()->getId()) {
            return $this->redirectToRoute('main_home');
        }

        if(strcasecmp($outing->getState(), "en creation") == 0){
            $outing->setState("OUVERT");
            $entityManager->flush();
        }

        return $this->redirectToRoute('main_home');
    }

    #[Route('/register/{id}', name: 'app_outing_register')]
    public function register(Outing $outing, EntityManagerInterface $entityManager, Security $security): Response
    {
        if ($outing->getIdOrganizer()->getId() == $security->getUser()->getId()) {
            return $this->redirectToRoute('main_home');
        }

        if(strcasecmp($outing->getState(), "en creation") == 0){
            $outing->setState("OUVERT");
            $entityManager->flush();
        }

        return $this->redirectToRoute('main_home');
    }

    #[Route('/{id}', name: 'app_outing_delete', methods: ['POST'])]
    public function delete(Request $request, Outing $outing, EntityManagerInterface $entityManager, Security $security): Response
    {
        if ($outing->getIdOrganizer()->getId() != $security->getUser()->getId()) {
            return $this->redirectToRoute('main_home');
        }

        if ($this->isCsrfTokenValid('delete' . $outing->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($outing);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_outing_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/withdrew/{id}', name: 'app_outing_withdrew')]
    public function withdrew (Outing $outing, Security $security, EntityManagerInterface $entityManager): Response
    {
        if(!$outing->getIdMember()->contains($security->getUser())) {
            return $this->redirectToRoute('main_home');
        }

        if(strcasecmp($outing->getState(), "ouvert") == 0){
            $outing->getIdMember()->removeElement($security->getUser());

            if($outing->getRegistrationDeadline() < new \DateTime()){
                $outing->setSlots($outing->getSlots() + 1);
            }

            $entityManager->flush();
        }

        return $this->redirectToRoute('main_home');
    }
}
