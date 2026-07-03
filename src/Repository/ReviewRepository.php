<?php

namespace App\Repository;

use App\Entity\Review;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Review>
 * Repository a Review entitás adatbázis-műveleteinek kezelésére.
 */
class ReviewRepository extends ServiceEntityRepository
{
    /**
     * Inicializálja a Review repository-t.
     *
     * @param ManagerRegistry $registry A Doctrine registry példánya.
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Review::class);
    }

    /**
     * Lekéri a legfrissebb véleményeket.
     *
     * @param int $limit A visszaadandó elemek maximális száma.
     *
     * @return Review[] A legfrissebb vélemények.
     */
    public function findLatestReviews(int $limit = 5): array
    {
        $limit = max(1, $limit);

        return $this->createQueryBuilder('review')
            ->orderBy('review.createdAt', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    /**
     * Lekéri a cégenkénti statisztikákat.
     *
     * Visszaadja a cégekhez tartozó vélemények számát,
     * valamint az átlagos értékelést.
     *
     * @return array<int, array<string, mixed>> A cégek statisztikai adatai.
     */
    public function getCompanyStatistics(): array
    {
        return $this->createQueryBuilder('review')
            ->select(
                'review.companyName AS companyName',
                'COUNT(review.id) AS reviewCount',
                'AVG(review.rating) AS averageRating'
            )
            ->groupBy('review.companyName')
            ->orderBy('averageRating', 'DESC')
            ->getQuery()
            ->getArrayResult();
    }

    /**
     * Létrehozza a véleménylista lekérdezéséhez szükséges QueryBuilder példányt.
     *
     * Opcionálisan cégnév szerinti keresést is alkalmaz.
     *
     * @param string|null $search A keresett cégnév.
     *
     * @return QueryBuilder A konfigurált QueryBuilder.
     */
    public function createListQueryBuilder(?string $search = null): QueryBuilder
    {
        $qb = $this->createQueryBuilder('review');

        if (!empty($search)) {
            $qb
                ->andWhere('LOWER(review.companyName) LIKE LOWER(:search)')
                ->setParameter('search', '%' . trim($search) . '%');
        }

        return $qb;
    }

    /**
     * Lekéri a legmagasabb értékelésű véleményeket.
     *
     * Azonos értékelés esetén a legfrissebb vélemények kerülnek előre.
     *
     * @param int $limit A visszaadandó elemek maximális száma.
     *
     * @return Review[] A legjobb értékelésű vélemények.
     */
    public function findTopRatedReviews(int $limit = 4): array
    {
        $limit = max(1, $limit);

        return $this->createQueryBuilder('review')
            ->orderBy('review.rating', 'DESC')
            ->addOrderBy('review.createdAt', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    /**
     * Ellenőrzi, hogy adott e-mail címről érkezett-e már vélemény
     * ugyanahhoz a céghez.
     *
     * @param string $companyName A vizsgált cég neve.
     * @param string $email A vizsgált e-mail cím.
     *
     * @return bool Igaz, ha már létezik ilyen vélemény, különben hamis.
     */
    public function hasReviewForCompanyAndEmail(
        string $companyName,
        string $email
    ): bool
    {
        /* egy nagy adatbázissal rendelkező oldalnál ez kevésbé jó megoldás hogy mindent lekér, egyedibb megoldás kellene
        itt viszont működik */
        $reviews = $this->findBy([
            'authorEmail' => $email,
        ]);

        foreach ($reviews as $review) {

            if ($review->getCompanyName() === $companyName) {
                return true;
            }
        }

        return false;
    }

    /**
     * Lekéri az összes véleményt exportálás céljából.
     *
     * @return Review[] Az exportálható vélemények.
     */
    public function findReviewsForExport(): array
    {
        return $this->createQueryBuilder('review')
            ->orderBy('review.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }
    
}
