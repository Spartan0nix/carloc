<?php

namespace App\Tests\Controller\Api;

use App\Tests\FixtureTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ApiTypeControllerTest extends WebTestCase
{
    use FixtureTrait;

    public function testGetType(): void {
        $client = $this->createClient();
        $data = $this->load(['type'], $client->getContainer());

        $client->request('GET', '/api/search/type', [
            'q' => $data['sportive']->getType()
        ]);

        $json_response = $client->getResponse()->getContent();
        $reponse = json_decode($json_response, true)['data'][0];
        $this->assertEquals($reponse, [
            'id' => $data['sportive']->getId(),
            'type' => $data['sportive']->getType(),
        ]);
    }
}