<?php

namespace App\Controller;

use App\Entity\Review;
use App\Form\ReviewFilterType;
use App\Form\ReviewType;
use App\Repository\ReviewRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\StreamedResponse;

final class ReviewController extends AbstractController
{
    #[Route('/reviews', name: 'review_index', methods: ['GET'])]

    /**
     * Megjeleníti a vélemények listáját.
     *
     * Kezeli a keresést, az oldalanként megjelenő elemek számát,
     * valamint a lapozást a KnpPaginator segítségével.
     *
     * @param Request $request Az aktuális HTTP kérés.
     * @param ReviewRepository $reviewRepository A vélemények lekérdezéséért felelős repository.
     * @param PaginatorInterface $paginator A lista lapozását végző szolgáltatás.
     *
     * @return Response A vélemények listáját megjelenítő oldal.
     */
    public function index(
        Request $request,
        ReviewRepository $reviewRepository,
        PaginatorInterface $paginator
    ): Response {

        $filterForm = $this->createForm(ReviewFilterType::class);

        $filterForm->handleRequest($request);

        $data = $filterForm->getData();

        $search = trim($data['search'] ?? '');

        $queryBuilder = $reviewRepository->createListQueryBuilder($search);

        $limit = (int) ($data['limit'] ?? 10);
        $allowedLimits = [10,25,50,100];
        if (!in_array($limit, $allowedLimits, true)) {
            $limit = 10;
        }

        $reviews = $paginator->paginate(
            $queryBuilder,
            max(1, $request->query->getInt('page', 1)),
            $limit
        );

        return $this->render('review/index.html.twig', [
            'reviews' => $reviews,
            'filterForm' => $filterForm->createView(),
        ]);
    }

    #[Route('/review/new', name: 'review_new', methods: ['GET', 'POST'])]

    /**
     * Új vélemény létrehozása.
     *
     * Megjeleníti és feldolgozza a vélemény beküldő űrlapot,
     * valamint ellenőrzi, hogy ugyanahhoz a céghez ugyanazzal
     * az e-mail címmel még ne létezzen korábbi értékelés.
     *
     * @param Request $request Az aktuális HTTP kérés.
     * @param EntityManagerInterface $entityManager Az entitások mentéséért felelős szolgáltatás.
     * @param ReviewRepository $reviewRepository A vélemények lekérdezéséért felelős repository.
     *
     * @return Response Az űrlapot vagy sikeres mentés után az átirányítást tartalmazó válasz.
     */
    public function new(
        Request $request,
        EntityManagerInterface $entityManager,
        ReviewRepository $reviewRepository
    ): Response {

        $review = new Review();

        $form = $this->createForm(ReviewType::class, $review);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            if (
                $reviewRepository->hasReviewForCompanyAndEmail(
                    $review->getCompanyName(),
                    $review->getAuthorEmail()
                )
            ) {
                $form->get('authorEmail')->addError(
                    new FormError('Ehhez a céghez ezzel az e-mail címmel már található vélemény.')
                );

                return $this->render('review/new.html.twig', [
                    'form' => $form->createView(),
                ]);
            }

            $entityManager->persist($review);

            $entityManager->flush();

            $this->addFlash(
                'success',
                'Köszönjük a véleményed!'
            );

            return $this->redirectToRoute('review_index');
        }

        return $this->render('review/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route(
        '/review/{id}',
        name: 'review_show',
        requirements: ['id' => '\d+'],
        methods: ['GET']
    )]

    /**
     * Megjeleníti egy kiválasztott vélemény részletes adatait.
     *
     * @param Review $review A megjelenítendő vélemény.
     *
     * @return Response A vélemény részleteit megjelenítő oldal.
     */
    public function show(Review $review): Response
    {
        return $this->render('review/show.html.twig', [
            'review' => $review,
        ]);
    }

    #[Route('/reviews/export', name: 'review_export', methods: ['GET'])]

    /**
     * Exportálja az összes véleményt CSV formátumban.
     *
     * A CSV fájl UTF-8 BOM-mal kerül létrehozásra,
     * így Microsoft Excel alatt is megfelelő karakterkódolással nyitható meg.
     *
     * @param ReviewRepository $reviewRepository A vélemények lekérdezéséért felelős repository.
     *
     * @return StreamedResponse A generált CSV fájl letöltését biztosító válasz.
     */
    public function export(
        ReviewRepository $reviewRepository
    ): StreamedResponse {

        $reviews = $reviewRepository->findReviewsForExport();

        $response = new StreamedResponse(function () use ($reviews) {

            $handle = fopen('php://output', 'w');

            if ($handle === false) {
                throw new \RuntimeException('Nem sikerült megnyitni a kimeneti streamet.');
            }

            // UTF-8 BOM Excel miatt
            fwrite($handle, "\xEF\xBB\xBF");

            fputcsv($handle, [
                'Cégnév',
                'Értékelés',
                'Vélemény',
                'E-mail',
                'Dátum'
            ], ';');

            foreach ($reviews as $review) {

                fputcsv($handle, [

                    $review->getCompanyName(),
                    $review->getRating(),
                    $review->getReviewText(),
                    $review->getAuthorEmail(),
                    $review->getCreatedAt()->format('Y-m-d H:i'),

                ], ';');
            }

            fclose($handle);
        });

        $response->headers->set('Content-Type','text/csv; charset=UTF-8');
        $response->headers->set('Cache-Control', 'no-store');
        
        $response->headers->set(
            'Content-Disposition',
            'attachment; filename="reviews.csv"'
        );

        return $response;
    }
}