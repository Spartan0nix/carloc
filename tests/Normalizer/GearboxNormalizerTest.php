<?php

namespace App\Tests\Normalizer;

use App\Normalizer\GearboxNormalizer;
use App\Tests\FixtureTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class GearboxNormalizerTest extends WebTestCase
{
    use FixtureTrait;

    public function testNormalize(): void {
        $data = $this->load(['gearbox'], $this->createClient()->getContainer());
        $gearbox = $data['manuelle'];
        dump($gearbox);

        $expected_array = [
            'id' => $gearbox->getId(),
            'gearbox' => $gearbox->getGearbox(),
        ];

        $normalizer = $this->createMock(GearboxNormalizer::class);
        $normalizer->expects($this->once())
                   ->method('normalize')
                   ->willReturn($expected_array);
        
        $normalizer->normalize($gearbox);
    }
}