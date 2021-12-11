<?php

namespace App\Tests\Controller\Api;

use App\Tests\FixtureTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ApiUserControllerTest extends WebTestCase
{
    use FixtureTrait;

    public function testGetUser(): void {
        $client = $this->createClient();
        $data = $this->load(['user'], $client->getContainer());

        $client->request('GET', '/api/search/user', [
            'q' => $data['admin']->getEmail()
        ]);

        $json_response = $client->getResponse()->getContent();
        $reponse = json_decode($json_response, true)['data'][0];
        $this->assertEquals($reponse, [
            'id' => $data['admin']->getId(),
            'email' => $data['admin']->getEmail(),
            'last_name' => $data['admin']->getLastName(),
            'fist_name' => $data['admin']->getFirstName()
        ]);
    }

    public function testGetUserId(): void {
        $client = $this->createClient();
        $data = $this->load(['user'], $client->getContainer());

        $client->request('GET', '/api/search/user_id', [
            'id' => $data['admin']->getId()
        ]);

        $json_response = $client->getResponse()->getContent();
        $response = json_decode($json_response, true)['data'];
        $this->assertEquals($response, [
            'id' => $data['admin']->getId(),
            'email' => $data['admin']->getEmail(),
            'last_name' => $data['admin']->getLastName(),
            'fist_name' => $data['admin']->getFirstName()
        ]);
    }
}