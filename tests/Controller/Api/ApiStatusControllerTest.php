<?php

namespace App\Tests\Controller\Api;

use App\Tests\FixtureTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ApiStatusControllerTest extends WebTestCase
{
    use FixtureTrait;

    public function testGetStatus(): void {
        $client = $this->createClient();
        $data = $this->load(['status'], $client->getContainer());

        $client->request('GET', '/api/search/status', [
            'q' => $data['payementValid']->getStatus()
        ]);

        $json_response = $client->getResponse()->getContent();
        $reponse = json_decode($json_response, true)['data'][0];
        $this->assertEquals($reponse, [
            'id' => $data['payementValid']->getId(),
            'status' => $data['payementValid']->getStatus(),
        ]);
    }

    public function testGetStatusId(): void {
        $client = $this->createClient();
        $data = $this->load(['status'], $client->getContainer());

        $client->request('GET', '/api/search/status_id', [
            'id' => $data['payementValid']->getId()
        ]);

        $json_response = $client->getResponse()->getContent();
        $response = json_decode($json_response, true)['data'];
        $this->assertEquals($response, [
            'id' => $data['payementValid']->getId(),
            'status' => $data['payementValid']->getStatus()
        ]);
    }
}