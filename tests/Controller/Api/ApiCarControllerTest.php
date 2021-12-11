<?php

namespace App\Tests\Controller\Api;

use App\Tests\FixtureTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ApiCarControllerTest extends WebTestCase
{
    use FixtureTrait;

    public function testGetCar(): void {
        $client = $this->createClient();
        $data = $this->load(['car'], $client->getContainer());

        $client->request('GET', '/api/search/car', [
            'q' => $data['M2']->getModelId()->getModel()
        ]);

        $json_response = $client->getResponse()->getContent();
        $response = json_decode($json_response, true)['data'][0];
        $this->assertEquals($response, [
            'id' => $data['M2']->getId(),
            'brand' => $data['M2']->getBrandId()->getBrand(),
            'model' => $data['M2']->getModelId()->getModel()
        ]);
    }

    public function testGetCityId(): void {
        $client = $this->createClient();
        $data = $this->load(['car'], $client->getContainer());

        $client->request('GET', '/api/search/car_id', [
            'id' => $data['M2']->getId()
        ]);

        $json_response = $client->getResponse()->getContent();
        $response = json_decode($json_response, true)['data'];
        $this->assertEquals($response, [
            'id' => $data['M2']->getId(),
            'brand' => $data['M2']->getBrandId()->getBrand(),
            'model' => $data['M2']->getModelId()->getModel(),
        ]);
    }
}