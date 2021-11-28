<?php

namespace App\Tests\Helper;

use App\Helper\BuildRentInfo;
use App\Tests\FixtureTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class BuildRentInfoTest extends WebTestCase
{
    use FixtureTrait;

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

        $controller = $this->createMock(BuildRentInfo::class);
        $controller->expects($this->once())
                       ->method('build')
                       ->will($this->returnValue($expected_array));

        $controller->build($request);
    }
}