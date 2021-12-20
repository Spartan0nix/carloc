<?php

namespace App\Tests\Controller;

use App\Tests\FixtureTrait;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;

class CarControllerTest extends WebTestCase
{
    use FixtureTrait;

    public function testListAvailableCar(): void {
        $client = $this->createClient();
        $container = $client->getContainer();
        $offices = $this->load(['office'], $container);
        $office_id = $offices['office1']->getId();

        $request = new Request();
        $request->setSession(new Session(new MockArraySessionStorage()));
        $request->getSession()->set('rent_info', [
            'pickup_office' => $office_id,
            'return_office' => $office_id,
            'pickup_date' =>  new DateTime('now'),
            'return_date' =>  new DateTime('+2 day'),
        ]);
    
        $request->create('/voitures', 'GET');

        $response = new Response();
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testGetCarDetails(): void {
        $client = $this->createClient();
        $cars = $this->load(['car'], $client->getContainer());
        $car_id = $cars['M2']->getId();

        $client->request('GET', "/voiture/{$car_id}/details");

        $this->assertResponseIsSuccessful();
    }
}