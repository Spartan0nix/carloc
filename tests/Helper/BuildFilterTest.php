<?php

namespace App\Helper;

use App\Tests\FixtureTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class BuildFilterTest extends WebTestCase
{
    use FixtureTrait;

    public function testBuildFilter(): void {
        $data = $this->load([
            'brand', 
            'model',
            'type',
            'fuel',
            'gearbox',
        ], $this->createClient()->getContainer());

        $expected_array = [
            'brand' => [
                [ 'id' => $data['bmw']->getId(), 'brand' => $data['bmw']->getBrand() ],
                [ 'id' => $data['audi']->getId(), 'brand' => $data['audi']->getBrand() ]
            ],
            'model' => [
                [ 'id' => $data['m2']->getId(), 'model' => $data['m2']->getModel(), ],
                [ 'id' => $data['rs3']->getId(), 'model' => $data['rs3']->getModel(), ]
            ],
            'type' => [
                [ 'id' => $data['sportive']->getId(), 'type' => $data['sportive']->getType(), ],
                [ 'id' => $data['coupe']->getId(), 'type' => $data['coupe']->getType(), ]
            ],
            'fuel' => [ 'id' => $data['essence']->getId(), 'fuel' => $data['essence']->getFuel() ],
            'gearbox' => [ 'id' => $data['manuelle']->getId(), 'gearbox' => $data['manuelle']->getGearbox() ]
        ];
        
        $controller = $this->createMock(BuildFilter::class);
        $controller->expects($this->once())
                   ->method('buildFilter')
                   ->will($this->returnValue($expected_array));

        $controller->buildFilter([
            'brand' => [ $data['bmw']->getId(), $data['audi']->getId()],
            'model' => [ $data['m2']->getId(), $data['rs3']->getId()],
            'type' => [ $data['sportive']->getId(), $data['coupe']->getId()],
            'fuel' => [ $data['essence']->getId() ],
            'gearbox' => [ $data['manuelle']->getId() ],
        ]);       
    }
}