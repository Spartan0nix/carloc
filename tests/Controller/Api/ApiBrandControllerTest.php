<?php

namespace App\Tests\Controller\Api;

use App\Tests\FixtureTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ApiBrandControllerTest extends WebTestCase
{
    use FixtureTrait;

    public function testGetBrand(): void {
        $client = $this->createClient();
        $data = $this->load(['brand'], $client->getContainer());

        $client->request('GET', '/api/search/brand', [
            'q' => 'bmw'
        ]);

        $json_response = $client->getResponse()->getContent();
        $response = json_decode($json_response, true)['data'][0];
        $this->assertEquals($response, [
            'id' => $data['bmw']->getId(),
            'brand' => $data['bmw']->getBrand()
        ]);
    }
}