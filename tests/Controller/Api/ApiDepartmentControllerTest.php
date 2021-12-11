<?php

namespace App\Tests\Controller\Api;

use App\Tests\FixtureTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ApiDepartmentControllerTest extends WebTestCase
{
    use FixtureTrait;

    public function testGetDepartment(): void {
        $client = $this->createClient();
        $data = $this->load(['department'], $client->getContainer());

        $client->request('GET', '/api/search/department', [
            'q' => $data['department1']->getName()
        ]);

        $json_reponse = $client->getResponse()->getContent();
        $response = json_decode($json_reponse, true)['data'][0];
        $this->assertEquals($response, [
            'id' => $data['department1']->getId(),
            'name' => $data['department1']->getName(),
            'code' => $data['department1']->getCode()
        ]);
    }

    public function testGetDepartmentId(): void {
        $client = $this->createClient();
        $data = $this->load(['department'], $client->getContainer());

        $client->request('GET', '/api/search/department_id', [
            'id' => $data['department1']->getId()
        ]);

        $json_reponse = $client->getResponse()->getContent();
        $response = json_decode($json_reponse, true)['data'];
        $this->assertEquals($response, [
            'id' => $data['department1']->getId(),
            'name' => $data['department1']->getName(),
            'code' => $data['department1']->getCode()
        ]);
    }
}