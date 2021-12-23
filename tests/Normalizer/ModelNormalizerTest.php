<?php

namespace App\Tests\Normalizer;

use App\Normalizer\ModelNormalizer;
use App\Tests\FixtureTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ModelNormalizerTest extends WebTestCase
{
    use FixtureTrait;

    public function testNormalize(): void {
        $data = $this->load(['model'], $this->createClient()->getContainer());
        $model = $data['m2'];

        $expected_array = [
            'id' => $model->getId(),
            'model' => $model->getModel(),
        ];

        $normalizer = $this->createMock(ModelNormalizer::class);
        $normalizer->expects($this->once())
                   ->method('normalize')
                   ->willReturn($expected_array);
        
        $normalizer->normalize($model);
    }
}