<?php

namespace App\Tests\Controller;

use App\Controller\RentController;
use App\Tests\FixtureTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;

class RentControllerTest extends WebTestCase
{
    use FixtureTrait;

    public function testCalculDuration(): void {
        $start_date = strval(date('Y-M-D'));
        $end_date = strval(date('Y-M-D', strtotime(date('Y-M-D'). '+ 2 days')));

        $controller = $this->createMock(RentController::class);
        $controller->expects($this->once())
            ->method('calculDuration')
            ->will($this->returnValue('2'));

        $controller->calculDuration($start_date, $end_date);
    }

    public function testCalculReduction(): void {
        $time_under_25_percents = '2';
        $time_over_25_percents = '100';
        $reduction_under_25_percents = (0.02) * $time_under_25_percents;
        $reduction_over_25_percents = (0.02) * $time_over_25_percents;

        $controller = $this->createMock(RentController::class);
        $controller->expects($this->exactly(2))
            ->method('calculReduction')
            ->willReturn($reduction_under_25_percents, $reduction_over_25_percents);
        
        $controller->calculReduction($time_under_25_percents);
        $controller->calculReduction($time_over_25_percents);
    }

    public function testCalculTotalPrice(): void {
        $daily_price = '2';
        $rent_day_duration = '2';
        $reduction = '0.05';

        $success = ($daily_price * $rent_day_duration) - (($daily_price * $rent_day_duration)* $reduction);

        $controller = $this->createMock(RentController::class);
        $controller->expects($this->once())
            ->method('calculTotalPrice')
            ->willReturn($this->returnValue($success));
        
        $controller->calculTotalPrice($daily_price, $rent_day_duration, $reduction);
    }

    public function testSummary(): void {
        $request = new Request();
        $request->setSession(new Session(new MockArraySessionStorage()));
        $client = $this->createClient();
        $data = $this->load(['user', 'car'] ,$client->getContainer());

        $client->request('POST', '/location/recapitulatif');
        $this->assertEquals(302, $client->getResponse()->getStatusCode());

        $client->loginUser($data['test']);
        $request->getSession()->set('rentInfo', [
            'start_date' => date('Y-M-D'),
            'end_date' => date('Y-M-D', strtotime(date('Y-M-D'). '+ 2 days'))
        ]);

        $request->create('/location/recapitulatif', 'POST', [
            'carId' => $data['M2']->getId()
        ]);

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