<?php

namespace App\Controller;

use App\Repository\ReviewRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;

final class HomeController extends AbstractController
{
    #[Route('/', name: 'home', methods: ['GET'])]

    /**
     * Megjeleníti a főoldalt a kiválasztott véleménylistával.
     *
     * A "type" lekérdezési paraméter alapján a legfrissebb vagy a
     * legjobbra értékelt vélemények kerülnek megjelenítésre.
     * Érvénytelen paraméter esetén a legfrissebb vélemények jelennek meg.
     *
     * @param Request $request Az aktuális HTTP kérés.
     * @param ReviewRepository $reviewRepository A vélemények lekérdezéséért felelős repository.
     *
     * @return Response A főoldal megjelenítéséhez szükséges válasz.
     */
    public function index(
        Request $request,
        ReviewRepository $reviewRepository
    ): Response {

        $type = $request->query->get('type', 'latest');
        
        $allowedTypes = ['latest', 'top'];

        if (!in_array($type, $allowedTypes, true)) {
            $type = 'latest';
        }

        $reviews = match ($type) {
            'top' => $reviewRepository->findTopRatedReviews(4),
            default => $reviewRepository->findLatestReviews(4),
        };

        return $this->render('home/index.html.twig', [

            'reviews' => $reviews,

            'type' => $type,

        ]);
    }
}