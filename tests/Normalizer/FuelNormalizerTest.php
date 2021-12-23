<?php

namespace App\Tests\Normalizer;

use App\Normalizer\FuelNormalizer;
use App\Tests\FixtureTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class FuelNormalizerTest extends WebTestCase
{
    use FixtureTrait;

    public function testNormalize(): void {
        $data = $this->load(['fuel'], $this->createClient()->getContainer());
        $fuel = $data['essence'];

        $expected_array = [
            'id' => $fuel->getId(),
            'fuel' => $fuel->getFuel()
        ];

        $normalizer = $this->createMock(FuelNormalizer::class);
        $normalizer->expects($this->once())
                   ->method('normalize')
                   ->willReturn($expected_array);
        
        $normalizer->normalize($fuel);
    }
}