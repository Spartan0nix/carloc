<?php

namespace App\Tests\Controller\Api;

use App\Tests\FixtureTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ApiCityControllerTest extends WebTestCase
{
    use FixtureTrait;

    public function testGetCity(): void {
        $client = $this->createClient();
        $data = $this->load(['city'], $client->getContainer());

        $client->request('GET', '/api/search/city', [
            'q' => $data['city1']->getName()
        ]);

        $json_response = $client->getResponse()->getContent();
        $response = json_decode($json_response, true)['data'][0];
        $this->assertEquals($response, [
            'id' => $data['city1']->getId(),
            'name' => $data['city1']->getName(),
            'code' => $data['city1']->getCode()
        ]);
    }

    public function testGetCityId(): void {
        $client = $this->createClient();
        $data = $this->load(['city'], $client->getContainer());

        $client->request('GET', '/api/search/city_id', [
            'id' => $data['city1']->getId()
        ]);

        $json_response = $client->getResponse()->getContent();
        $response = json_decode($json_response, true)['city'];
        $this->assertEquals($response, [
            'id' => $data['city1']->getId(),
            'name' => $data['city1']->getName(),
            'code' => $data['city1']->getCode()
        ]);
    }
}