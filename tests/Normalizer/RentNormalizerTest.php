<?php

namespace App\Tests\Normalizer;

use App\Normalizer\RentNormalizer;
use App\Tests\FixtureTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RentNormalizerTest extends WebTestCase
{
    use FixtureTrait;

    public function testNormalize(): void {
        $data = $this->load(['rent'], $this->createClient()->getContainer());
        $rent = $data['rent'];

        $expected_array = [
            'id' => $rent->getId(),
            'price' => $rent->getPrice(),
            'pickup_date' => $rent->getPickupDate(),
            'return_date' => $rent->getReturnDate(),
            'user' => $rent->getUserId()->getId(),
            'car' => $rent->getCarId()->getId(),
            'status' => $rent->getStatusId()->getStatus(),
        ];

        $expected_array_extended = [
            'id' => $rent->getId(),
            'price' => $rent->getPrice(),
            'pickup_date' => $rent->getPickupDate(),
            'return_date' => $rent->getReturnDate(),
            'user' => $rent->getUserId()->getId(),
            'car' => $rent->getCarId()->getId(),
            'status' => [
                'id' => $rent->getStatusId()->getId(),
                'status' => $rent->getStatusId()->getStatus(),
            ],
            'pickup_office' => [
                'id' => $rent->getPickupOfficeId()->getId(),
                'street' => $rent->getPickupOfficeId()->getStreet(),
                'tel_number' => $rent->getPickupOfficeId()->getTelNumber(),
                'email' => $rent->getPickupOfficeId()->getEmail(),
                'city' => [
                    'id' => $rent->getPickupOfficeId()->getCityId()->getId(),
                    'name' => $rent->getPickupOfficeId()->getCityId()->getName(),
                    'code' => $rent->getPickupOfficeId()->getCityId()->getCode(),
                ],
                'department' => [
                    'id' => $rent->getPickupOfficeId()->getDepartmentId()->getId(),
                    'name' => $rent->getPickupOfficeId()->getDepartmentId()->getName(),
                    'code' => $rent->getPickupOfficeId()->getDepartmentId()->getCode(),
                ]
            ],
            'return_office' => [
                'id' => $rent->getReturnOfficeId()->getId(),
                'street' => $rent->getReturnOfficeId()->getStreet(),
                'tel_number' => $rent->getReturnOfficeId()->getTelNumber(),
                'email' => $rent->getReturnOfficeId()->getEmail(),
                'city' => [
                    'id' => $rent->getReturnOfficeId()->getCityId()->getId(),
                    'name' => $rent->getReturnOfficeId()->getCityId()->getName(),
                    'code' => $rent->getReturnOfficeId()->getCityId()->getCode(),
                ],
                'department' => [
                    'id' => $rent->getReturnOfficeId()->getDepartmentId()->getId(),
                    'name' => $rent->getReturnOfficeId()->getDepartmentId()->getName(),
                    'code' => $rent->getReturnOfficeId()->getDepartmentId()->getCode(),
                ]
            ],
            'user' => [
                'id' => $rent->getUserId()->getId(),
                'email' => $rent->getUserId()->getEmail(),
                'roles' => $rent->getUserId()->getRoles(),
                'last_name' => $rent->getUserId()->getLastName(),
                'first_name' => $rent->getUserId()->getFirstName(),
                'address' => $rent->getUserId()->getAddress(),
                'city' => [
                    'id' => $rent->getUserId()->getCityId()->getId(),
                    'name' => $rent->getUserId()->getCityId()->getName(),
                    'code' => $rent->getUserId()->getCityId()->getCode(),
                ],
                'department' => [
                    'id' => $rent->getUserId()->getDepartmentId()->getId(),
                    'name' => $rent->getUserId()->getDepartmentId()->getName(),
                    'code' => $rent->getUserId()->getDepartmentId()->getCode(),
                ]
            ],
            'car' => [
                'id' => $rent->getCarId()->getId(),
                'horsepower' => $rent->getCarId()->getHorsepower(),
                'description' => $rent->getCarId()->getDescription(),
                'daily_price' => $rent->getCarId()->getDailyPrice(),
                'release_year' => $rent->getCarId()->getReleaseYear(),
                'fuel' => [
                    'id' => $rent->getCarId()->getFuelId()->getId(),
                    'fuel' => $rent->getCarId()->getFuelId()->getFuel()
                ],
                'brand' => [
                    'id' => $rent->getCarId()->getBrandId()->getId(),
                    'brand' => $rent->getCarId()->getBrandId()->getBrand()
                ],
                'model' => [
                    'id' => $rent->getCarId()->getModelId()->getId(),
                    'model' => $rent->getCarId()->getModelId()->getModel()
                ],
                'color' => [
                    'id' => $rent->getCarId()->getColorId()->getId(),
                    'color' => $rent->getCarId()->getColorId()->getColor()
                ],
                'gearbox' => [
                    'id' => $rent->getCarId()->getGearboxId()->getId(),
                    'gearbox' => $rent->getCarId()->getGearboxId()->getGearbox()
                ],
            ]
        ];

        $normalizer = $this->createMock(RentNormalizer::class);
        $normalizer->expects($this->exactly(2))
                   ->method('normalize')
                   ->willReturn($expected_array, $expected_array_extended);
        
        $normalizer->normalize($rent);
        $normalizer->normalize($rent, 'extended');
    }
}