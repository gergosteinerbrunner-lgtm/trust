<?php

namespace App\Controller;

use App\Repository\ReviewRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class CompanyController extends AbstractController
{
    #[Route('/companies', name: 'company_index', methods: ['GET'])]

    /**
     * Megjeleníti a cégek összesített statisztikáit.
     *
     * A statisztikák tartalmazzák cégenként a beküldött vélemények
     * számát és az átlagos értékelést, csökkenő sorrendben rendezve.
     *
     * @param ReviewRepository $reviewRepository A vélemények lekérdezéséért felelős repository.
     *
     * @return Response A cégstatisztikákat megjelenítő oldal.
     */
    public function index(ReviewRepository $reviewRepository): Response
    {
        return $this->render('company/index.html.twig', [
            'companies' => $reviewRepository->getCompanyStatistics(),
        ]);
    }
}