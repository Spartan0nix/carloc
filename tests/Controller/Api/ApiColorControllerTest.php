<?php

namespace App\Tests\Controller\Api;

use App\Tests\FixtureTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ApiColorControllerTest extends WebTestCase
{
    use FixtureTrait;

    public function testGetColor(): void {
        $client = $this->createClient();
        $data = $this->load(['color'], $client->getContainer());

        $client->request('GET', '/api/search/color', [
            'q' => $data['color1']->getColor()
        ]);

        $json_response = $client->getResponse()->getContent();
        $response = json_decode($json_response, true)['data'][0];
        $this->assertEquals($response, [
            'id' => $data['color1']->getId(),
            'color' => $data['color1']->getColor()
        ]);
    }

    public function testGetColorId(): void {
        $client = $this->createClient();
        $data = $this->load(['color'], $client->getContainer());

        $client->request('GET', '/api/search/color_id', [
            'id' => $data['color1']->getId()
        ]);

        $json_response = $client->getResponse()->getContent();
        $response = json_decode($json_response, true)['data'];
        $this->assertEquals($response, [
            'id' => $data['color1']->getId(),
            'color' => $data['color1']->getColor(),
        ]);
    }
}