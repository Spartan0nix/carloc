<?php

namespace App\Tests\Normalizer;

use App\Normalizer\UserNormalizer;
use App\Tests\FixtureTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserNormalizerTest extends WebTestCase
{
    use FixtureTrait;

    public function testNormalize(): void {
        $data = $this->load(['user'], $this->createClient()->getContainer());
        $user = $data['test'];

        $expected_array = [
            'id' => $user->getId(),
            'email' => $user->getEmail(),
            'roles' => $user->getRoles(),
            'last_name' => $user->getLastName(),
            'first_name' => $user->getFirstName(),
        ];

        $expected_array_extended = [
            'id' => $user->getId(),
            'email' => $user->getEmail(),
            'roles' => $user->getRoles(),
            'last_name' => $user->getLastName(),
            'first_name' => $user->getFirstName(),
            'address' => $user->getAddress(),
            'city' => [
                'id' => $user->getCityId()->getId(),
                'name' => $user->getCityId()->getName(),
                'code' => $user->getCityId()->getCode(),
            ],
            'department' => [
                'id' => $user->getDepartmentId()->getId(),
                'name' => $user->getDepartmentId()->getName(),
                'code' => $user->getDepartmentId()->getCode(),
            ]
            
        ];

        $normalizer = $this->createMock(UserNormalizer::class);
        $normalizer->expects($this->exactly(2))
                   ->method('normalize')
                   ->willReturn($expected_array, $expected_array_extended);
        
        $normalizer->normalize($user);
        $normalizer->normalize($user, 'extended');
    }
}