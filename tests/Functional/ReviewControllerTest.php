<?php

namespace App\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ReviewControllerTest extends WebTestCase
{
    /**
     * Ellenőrzi, hogy a felhasználó sikeresen tud új véleményt létrehozni,
     * majd a rendszer átirányítja és sikeres visszajelzést jelenít meg.
     */
    public function testUserCanCreateReview(): void
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/review/new');

        $this->assertResponseIsSuccessful();

        $form = $crawler->selectButton('Vélemény beküldése')->form();

        $form['review[companyName]'] = 'Functional Test Company';
        $form['review[rating]'] = 5;
        $form['review[reviewText]'] = 'Ez egy PHPUnit functional teszt.';
        $form['review[authorEmail]'] = 'functional@test.hu';

        $client->submit($form);

        $this->assertResponseRedirects('/reviews');

        $client->followRedirect();

        $this->assertSelectorExists('.alert-success');

        $this->assertSelectorTextContains(
            '.alert-success',
            'Köszönjük a véleményed!'
        );

        $this->assertSelectorTextContains(
            'tbody',
            'Functional Test Company'
        );
    }
}