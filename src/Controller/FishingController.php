<?php

namespace App\Controller;

use App\Entity\Fishing;
use App\Form\FishingType;
use App\Repository\FishingRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/fishing')]
class FishingController extends AbstractController
{
    #[Route('/', name: 'app_fishing_index', methods: ['GET'])]
    #[Route('/fishing/{page}', name: 'fishing_paginer', methods: ['GET'])]
    public function index(
        FishingRepository $fishingRepository,
        int $page = 1
    ): Response
    {
        $nbfishing = $fishingRepository->findfishingPaginerCount();
        return $this->render('fishing/index.html.twig', [
            'fishings' => $fishingRepository->findFishingPaginer($page),
            'currentPage' => $page,
            'maxFishing' => $nbfishing > ($page * 10)
        ]);
    }

    #[Route('/fishing-search/{id}', name: 'fishing_search', methods: ['GET'])]
    // La classe FishingRepository permet d'effectuer les requêtes sql SELECT voulues via 4 methodes find()
    public function search($id, FishingRepository $fishingRepository): Response
    {
        $fishingSearch = $fishingRepository->find($id);
        return $this->render('fishing/search.html.twig', [
            'fishingSearch' => $fishingSearch
        ]);
    }

    #[Route('/search-results', name: 'search-result')]
    public function searchFishing(Request $request, FishingRepository $fishingRepository)
    {
        $search = $request->query->get('search');
        $fishingsSearches = $fishingRepository->searchFishing($search);

        return $this->render('fishing/search.html.twig', [
            'fishingsSearches' => $fishingsSearches,
            'search' => $search
        ]);
    }

    #[Route('/new', name: 'app_fishing_new', methods: ['GET', 'POST'])]
    public function new(Request $request, FishingRepository $fishingRepository): Response
    {
        $fishing = new Fishing();
        $form = $this->createForm(FishingType::class, $fishing);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $fishingRepository->add($fishing, true);

            return $this->redirectToRoute('app_fishing_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('fishing/new.html.twig', [
            'fishing' => $fishing,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_fishing_show', methods: ['GET'])]
    public function show(Fishing $fishing): Response
    {
        return $this->render('fishing/show.html.twig', [
            'fishing' => $fishing,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_fishing_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Fishing $fishing, FishingRepository $fishingRepository): Response
    {
        $form = $this->createForm(FishingType::class, $fishing);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $fishingRepository->add($fishing, true);

            return $this->redirectToRoute('app_fishing_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('fishing/edit.html.twig', [
            'fishing' => $fishing,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_fishing_delete', methods: ['POST'])]
    public function delete(Request $request, Fishing $fishing, FishingRepository $fishingRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$fishing->getId(), $request->request->get('_token'))) {
            $fishingRepository->remove($fishing, true);
        }

        return $this->redirectToRoute('app_fishing_index', [], Response::HTTP_SEE_OTHER);
    }
}
