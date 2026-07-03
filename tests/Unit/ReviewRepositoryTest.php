<?php

namespace App\Tests\Unit;

use App\Entity\Review;
use App\Repository\ReviewRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ReviewRepositoryTest extends KernelTestCase
{
    private EntityManagerInterface $entityManager;
    private ReviewRepository $repository;

    /**
     * Inicializálja a tesztkörnyezetet és előkészíti a szükséges szolgáltatásokat.
     */
    protected function setUp(): void
    {
        self::bootKernel();

        $container = static::getContainer();

        $this->entityManager = $container->get(EntityManagerInterface::class);
        $this->repository = $container->get(ReviewRepository::class);

        $this->entityManager->createQuery('DELETE FROM App\Entity\Review r')->execute();
    }

    /**
     * Felszabadítja a teszt során használt erőforrásokat.
     */
    protected function tearDown(): void
    {
        $this->entityManager->close();

        parent::tearDown();
    }

    /**
     * Ellenőrzi, hogy a cégstatisztikák helyesen számolódnak,
     * valamint átlagos értékelés szerint csökkenő sorrendben kerülnek visszaadásra.
     */
    public function testCompanyStatisticsAreCalculatedAndSorted(): void
    {
        $this->createReview('Google', 5);
        $this->createReview('Google', 4);
        $this->createReview('Google', 5);

        $this->createReview('Microsoft', 3);
        $this->createReview('Microsoft', 4);

        $this->entityManager->flush();

        $statistics = $this->repository->getCompanyStatistics();

        $this->assertCount(2, $statistics);

        $this->assertSame('Google', $statistics[0]['companyName']);
        $this->assertSame(3, $statistics[0]['reviewCount']);
        $this->assertEqualsWithDelta(4.6667, $statistics[0]['averageRating'], 0.01);

        $this->assertSame('Microsoft', $statistics[1]['companyName']);
        $this->assertSame(2, $statistics[1]['reviewCount']);
        $this->assertEqualsWithDelta(3.5, $statistics[1]['averageRating'], 0.01);
    }

    /**
     * Létrehoz és perzisztál egy teszt véleményt.
     *
     * @param string $company A tesztcég neve.
     * @param int $rating A teszt értékelése.
     */
    private function createReview(string $company, int $rating): void
    {
        $review = new Review();

        $review->setCompanyName($company);
        $review->setRating($rating);
        $review->setReviewText('Test review');
        $review->setAuthorEmail('test@example.com');

        $this->entityManager->persist($review);
    }
}