<?php

namespace App\DataFixtures;

use App\Entity\Review;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

/**
 * Tesztadatokat generáló fixture a Review entitáshoz.
 * 
 * Kizárólag fejlesztői és tesztkörnyezetben használható.
 */
class ReviewFixtures extends Fixture
{
    /**
     * Létrehozza a demó adatkészletet az alkalmazás számára.
     *
     * Öt különböző céghez generál véletlenszerű számú véleményt,
     * véletlen értékelésekkel és minta szövegekkel.
     *
     * @param ObjectManager $manager A Doctrine ObjectManager példánya.
     */
    public function load(ObjectManager $manager): void
    {
        $companies = [
            'Netflix',
            'Google',
            'Microsoft',
            'Apple',
            'Amazon',
        ];

        $reviews = [
            'Nagyon elégedett vagyok a szolgáltatással.',
            'Gyors ügyintézés és segítőkész ügyfélszolgálat.',
            'Korrekt cég, bátran ajánlom.',
            'Az elvárásaimnak teljesen megfelelt.',
            'Kiváló minőség és gyors kiszállítás.',
            'Átlagos élmény volt.',
            'Lehetne jobb is, de összességében rendben van.',
            'Minden a várakozásaim szerint történt.',
            'Professzionális hozzáállás.',
            'Biztosan újra igénybe veszem.',
            'Ritkán szoktam ilyen hosszú értékelést írni, de úgy érzem, hogy ez a szolgáltatás valóban megérdemli. Az első kapcsolatfelvételtől kezdve végig segítőkészek voltak, minden kérdésemre gyors és részletes választ kaptam. A rendelési folyamat egyszerű volt, a kiszállítás a megadott határidőn belül megtörtént, a termék pedig pontosan olyan állapotban érkezett meg, ahogyan azt vártam. Külön kiemelném az ügyfélszolgálat hozzáállását, mert ritkán találkozom ennyire udvarias és szakmailag felkészült munkatársakkal. Egy apró problémám ugyan felmerült a használat során, de ezt néhány órán belül megoldották, és folyamatosan tájékoztattak a javítás állapotáról. Összességében rendkívül pozitív tapasztalatokat szereztem, ezért biztos vagyok benne, hogy a jövőben is ezt a céget fogom választani, és bátran ajánlom mindenkinek, aki megbízható szolgáltatót keres. Számomra különösen fontos volt az átlátható kommunikáció, a gyors reakcióidő és az, hogy végig azt éreztem, valóban ügyfélként kezelnek, nem csak egy újabb rendelésként.'
        ];

        foreach ($companies as $company) {

            $count = random_int(3, 15);

            for ($i = 1; $i <= $count; $i++) {

                $review = new Review();

                $review->setCompanyName($company);

                $review->setRating(random_int(1, 5));

                $review->setReviewText(
                    $reviews[array_rand($reviews)]
                );

                $review->setAuthorEmail(
                    sprintf(
                        '%s%d@example.com',
                        strtolower($company),
                        $i
                    )
                );

                $review->setCreatedAt(
                    new \DateTimeImmutable(
                        '-' . random_int(0, 180) . ' days'
                    )
                );

                $review->setUpdatedAt(
                    new \DateTimeImmutable()
                );

                $manager->persist($review);
            }
        }

        $manager->flush();
    }
}