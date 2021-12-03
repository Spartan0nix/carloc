<?php

namespace App\Tests\Controller\Api;

use App\Tests\FixtureTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ApiFuelControllerTest extends WebTestCase
{
    use FixtureTrait;

    public function testGetFuel(): void {
        $client = $this->createClient();
        $data = $this->load(['fuel'], $client->getContainer());

        $client->request('GET', '/api/search/fuel', [
            'q' => $data['essence']->getFuel()
        ]);

        $json_response = $client->getResponse()->getContent();
        $reponse = json_decode($json_response, true)['data'][0];
        $this->assertEquals($reponse, [
            'id' => $data['essence']->getId(),
            'fuel' => $data['essence']->getFuel(),
        ]);
    }
}