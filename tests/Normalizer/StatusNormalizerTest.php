<?php

namespace App\Tests\Normalizer;

use App\Normalizer\StatusNormalizer;
use App\Tests\FixtureTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class StatusNormalizerTest extends WebTestCase
{
    use FixtureTrait;

    public function testNormalize(): void {
        $data = $this->load(['status'], $this->createClient()->getContainer());

        $expected_array = [
            'id' => $data['payementValid']->getId(),
            'status' => $data['payementValid']->getStatus(),
        ];

        $normalizer = $this->createMock(StatusNormalizer::class);
        $normalizer->expects($this->once())
                   ->method('normalize')
                   ->willReturn($expected_array);
        
        $normalizer->normalize($data['payementValid']);
    }
}