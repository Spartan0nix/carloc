<?php

namespace App\Tests\Normalizer;

use App\Normalizer\CarNormalizer;
use App\Tests\FixtureTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CityNormalizerTest extends WebTestCase
{
    use FixtureTrait;

    public function testNormalize(): void {
        $data = $this->load(['city'], $this->createClient()->getContainer());
        $city = $data['city1'];

        $expected_array = [
            'id' => $city->getId(),
            'name' => $city->getName(),
            'code' => $city->getCode(),
        ];

        $normalizer = $this->createMock(CarNormalizer::class);
        $normalizer->expects($this->once())
                   ->method('normalize')
                   ->willReturn($expected_array);
        
        $normalizer->normalize($city);
    }
}