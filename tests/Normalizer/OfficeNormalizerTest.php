<?php

namespace App\Tests\Normalizer;

use App\Normalizer\CarNormalizer;
use App\Tests\FixtureTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class OfficeNormalizerTest extends WebTestCase
{
    use FixtureTrait;

    public function testNormalize(): void {
        $data = $this->load(['office'], $this->createClient()->getContainer());
        $office = $data['office1'];

        $expected_array = [
            'id' => $office->getId(),
            'street' => $office->getStreet(),
            'tel_number' => $office->getTelNumber(),
            'email' => $office->getEmail(),
        ];

        $expected_array_extended = [
            'id' => $office->getId(),
            'street' => $office->getStreet(),
            'tel_number' => $office->getTelNumber(),
            'email' => $office->getEmail(),
            'city' => [
                'id' => $office->getCityId()->getId(),
                'name' => $office->getCityId()->getName(),
                'code' => $office->getCityId()->getCode(),
            ],
            'department' => [
                'id' => $office->getDepartmentId()->getId(),
                'name' => $office->getDepartmentId()->getName(),
                'code' => $office->getDepartmentId()->getCode(),
            ]
            
        ];

        $normalizer = $this->createMock(CarNormalizer::class);
        $normalizer->expects($this->exactly(2))
                   ->method('normalize')
                   ->willReturn($expected_array, $expected_array_extended);
        
        $normalizer->normalize($office);
        $normalizer->normalize($office, 'extended');
    }
}