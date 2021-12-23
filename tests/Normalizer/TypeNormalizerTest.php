<?php

namespace App\Tests\Normalizer;

use App\Normalizer\TypeNormalizer;
use App\Tests\FixtureTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TypeNormalizerTest extends WebTestCase
{
    use FixtureTrait;

    public function testNormalize(): void {
        $data = $this->load(['type'], $this->createClient()->getContainer());

        $expected_array = [
            'id' => $data['sportive']->getId(),
            'type' => $data['sportive']->getType()
        ];

        $normalizer = $this->createMock(TypeNormalizer::class);
        $normalizer->expects($this->once())
                   ->method('normalize')
                   ->willReturn($expected_array);
        
        $normalizer->normalize($data['sportive']);
    }
}