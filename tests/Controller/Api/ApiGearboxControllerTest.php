<?php

namespace App\Tests\Controller\Api;

use App\Tests\FixtureTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ApiGearboxControllerTest extends WebTestCase
{
    use FixtureTrait;

    public function testGetGearbox(): void {
        $client = $this->createClient();
        $data = $this->load(['gearbox'], $client->getContainer());

        $client->request('GET', '/api/search/gearbox', [
            'q' => $data['manuelle']->getGearbox()
        ]);

        $json_response = $client->getResponse()->getContent();
        $reponse = json_decode($json_response, true)['data'][0];
        $this->assertEquals($reponse, [
            'id' => $data['manuelle']->getId(),
            'gearbox' => $data['manuelle']->getGearbox(),
        ]);
    }
}