<?php

namespace App\Tests\Controller\Api;

use App\Tests\FixtureTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ApiModelControllerTest extends WebTestCase
{
    use FixtureTrait;

    public function testGetModel(): void {
        $client = $this->createClient();
        $data = $this->load(['model'], $client->getContainer());

        $client->request('GET', '/api/search/model', [
            'q' => $data['m2']->getModel()
        ]);

        $json_response = $client->getResponse()->getContent();
        $reponse = json_decode($json_response, true)['data'][0];
        $this->assertEquals($reponse, [
            'id' => $data['m2']->getId(),
            'model' => $data['m2']->getModel(),
        ]);
    }
}