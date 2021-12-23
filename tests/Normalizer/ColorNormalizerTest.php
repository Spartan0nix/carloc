<?php

namespace App\Tests\Normalizer;

use App\Normalizer\CityNormalizer;
use App\Tests\FixtureTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ColorNormalizerTest extends WebTestCase
{
    use FixtureTrait;

    public function testNormalize(): void {
        $data = $this->load(['color'], $this->createClient()->getContainer());
        $color = $data['color1'];

        $expected_array = [
            'id' => $color->getId(),
            'color' => $color->getColor()
        ];

        $normalizer = $this->createMock(CityNormalizer::class);
        $normalizer->expects($this->once())
                   ->method('normalize')
                   ->willReturn($expected_array);
        
        $normalizer->normalize($color);
    }
}