<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class OfficeControllerTest extends WebTestCase
{
    public function testIndex(): void {
        $client = $this->createClient();
        $client->request('GET', '/agence/list');

        $this->assertResponseIsSuccessful($client->getResponse());
    }
}