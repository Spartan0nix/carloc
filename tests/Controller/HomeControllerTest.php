<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class HomeControllerTest extends WebTestCase
{
    public function testIndex(): void {
        $client = $this->createClient();
        $client->request('GET', '/');

        $this->assertResponseIsSuccessful($client->getResponse());
    }
}