<?php

namespace App\Tests\Controller\Api;

use App\Tests\FixtureTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ApiOfficeControllerTest extends WebTestCase
{
    use FixtureTrait;

    public function testGetOffice(): void {
        $client = $this->createClient();
        $data = $this->load(['office'], $client->getContainer());

        $client->request('GET', '/api/search/office', [
            'q' => $data['office1']->getStreet()
        ]);

        $json_response = $client->getResponse()->getContent();
        $reponse = json_decode($json_response, true)['data'][0];
        $this->assertEquals($reponse, [
            'id' => $data['office1']->getId(),
            'street' => $data['office1']->getStreet(),
            'tel_number' => $data['office1']->getTelNumber(),
            'email' => $data['office1']->getEmail(),
        ]);
    }
}