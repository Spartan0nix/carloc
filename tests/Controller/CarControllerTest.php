<?php

namespace App\Tests\Controller;

use App\Controller\CarController;
use App\Repository\OfficeRepository;
use App\Tests\FixtureTrait;
use Symfony\Bundle\FrameworkBundle\Test\TestContainer;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CarControllerTest extends WebTestCase
{
    use FixtureTrait;

    public function testListAvailableCar(): void {
        $client = $this->createClient();
        $container = $client->getContainer();
        $csrf_token = $container->get('security.csrf.token_manager')->refreshToken('token');
        $offices = $this->load(['office'], $container);
        $office_id = $offices['office1']->getId();
    
        $client->request('POST', '/voiture/list', [
            'pickup_office' => $office_id,
            'return_office' => $office_id,
            'start_date' => date('Y-M-D'),
            'end_date' => date('Y-M-D', strtotime(date('Y-M-D'). '+ 2 days')),
            'token' => $csrf_token
        ]);
        
        $this->assertResponseIsSuccessful();
    }

    public function testBuildRentInfo(): void{
        $container = $this->createClient()->getContainer();
        $offices = $this->load(['office'], $container);
        $pickup_office_id = $offices['office1']->getId();
        $return_office_id = $offices['office2']->getId();

        $request = [
            'pickup_office' => $pickup_office_id,
            'return_office' => $return_office_id,
            'start_date' => date('Y-M-D'),
            'end_date' => date('Y-M-D', strtotime(date('Y-M-D'). '+ 2 days')),
            'token' => '16518691896016891681686516810651d$dadlemf'
        ];

        $expected_array = $request;
        unset($expected_array['token']);

        $car_controller = $this->createMock(CarController::class);
        $car_controller->expects($this->once())
                       ->method('buildRentInfo')
                       ->will($this->returnValue($expected_array));

        $car_controller->buildRentInfo($request);
    }
}