<?php

namespace App\Tests\Normalizer;

use App\Normalizer\BrandNormalizer;
use App\Tests\FixtureTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class BrandNormalizerTest extends WebTestCase
{
    use FixtureTrait;

    public function testNormalize(): void {
        $data = $this->load(['brand'], $this->createClient()->getContainer());

        $expected_array = [
            'id' => $data['bmw']->getId(),
            'brand' => $data['bmw']->getBrand(),
        ];

        $normalizer = $this->createMock(BrandNormalizer::class);
        $normalizer->expects($this->once())
                   ->method('normalize')
                   ->willReturn($expected_array);
        
        $normalizer->normalize($data['bmw']);
    }
}