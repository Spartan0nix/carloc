<?php

namespace App\Tests\Controller;

use App\Tests\FixtureTrait;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;
use Symfony\Component\Validator\Constraints\Date;

class RentControllerTest extends WebTestCase
{
    use FixtureTrait;

    public function testSummary(): void {
        $request = new Request();
        $request->setSession(new Session(new MockArraySessionStorage()));
        $client = $this->createClient();
        $data = $this->load(['user', 'car', 'office'] ,$client->getContainer());
        $car_id = $data['M2']->getId();

        $client->request('GET', "/location/{$car_id}/recapitulatif");
        $this->assertEquals(302, $client->getResponse()->getStatusCode());

        $client->loginUser($data['test']);
        $request->getSession()->set('rent_info', [
            'pickup_office' => $data['office1']->getId(),
            'return_office' => $data['office1']->getId(),
            'pickup_date' => new DateTime('now'),
            'return_date' => new DateTime('+2 day')
        ]);

        $request->create("/location/{$car_id}/recapitulatif", 'GET');

        $response = new Response();
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testConfirm(): void {
        $request = new Request();
        $request->setSession(new Session(new MockArraySessionStorage()));
        $data = $this->load(['office', 'car'], $this->createClient()->getContainer());

        $request->getSession()->set('rentInfo', [
            'pickup_office' => $data['office1']->getId(),
            'return_office' => $data['office1']->getId(),
            'start_date' => date('Y-M-D'),
            'end_date' => date('Y-M-D', strtotime(date('Y-M-D'). '+ 2 days'))
        ]);

        $request->create('/location/confirmation', 'POST', [
            'car_id' => $data['M2']->getId()
        ]);

        $response = new Response();
        $this->assertEquals(200, $response->getStatusCode());
    }
}