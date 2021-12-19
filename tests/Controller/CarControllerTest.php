<?php

namespace App\Tests\Controller;

use App\Tests\FixtureTrait;
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
    
        $client->request('POST', '/voitures', [
            'user_reservation' => [
                'pickup_office' => $office_id,
                'return_office' => $office_id,
                'pickup_date' => date('Y-M-D'),
                'return_date' => date('Y-M-D', strtotime(date('Y-M-D'). '+ 2 days')),
            ]
        ]);
        
        $this->assertResponseIsSuccessful($client->getResponse());
    }

    public function testFilterAvailableCar(): void {
        $request = New Request();
        $request->setSession(new Session((new MockArraySessionStorage())));
        $container = $this->createClient()->getContainer();
        $csrf_token = $container->get('security.csrf.token_manager')->refreshToken('_token');
        $data = $this->load(['office', 'brand', 'model', 'type', 'fuel', 'gearbox'], $container);

        $request->getSession()->set('rent_info', [
            'pickup_office' => $data['office1']->getId()
        ]);

        $request->create('/voitures/filtre', 'GET', [
            'car_filter' => [
                'brand_id' => [
                    '0' => $data['bmw']->getId()
                ],
                'model_id' => [
                    '0' => $data['m2']->getId()
                ],
                'type_id' => [
                    '0' => $data['sportive']->getId()
                ],
                'fuel_id' => [
                    '0' => $data['essence']->getId()
                ],
                'gearbox_id' => [
                    '0' => $data['manuelle']->getId()
                ],
                '_token' => $csrf_token
            ]
        ]);

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