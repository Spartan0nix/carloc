<?php

namespace App\Tests\Normalizer;

use App\Normalizer\DepartmentNormalizer;
use App\Tests\FixtureTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DepartmentNormalizerTest extends WebTestCase
{
    use FixtureTrait;

    public function testNormalize(): void {
        $data = $this->load(['department'], $this->createClient()->getContainer());
        $department = $data['department1'];

        $expected_array = [
            'id' => $department->getId(),
            'name' => $department->getName(),
            'code' => $department->getCode(),
        ];

        $normalizer = $this->createMock(DepartmentNormalizer::class);
        $normalizer->expects($this->once())
                   ->method('normalize')
                   ->willReturn($expected_array);
        
        $normalizer->normalize($department);
    }
}