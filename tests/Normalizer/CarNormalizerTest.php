<?php

namespace App\Tests\Normalizer;

use App\Normalizer\CarNormalizer;
use App\Tests\FixtureTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CarNormalizerTest extends WebTestCase
{
    use FixtureTrait;

    public function testNormalize(): void {
        $data = $this->load(['car'], $this->createClient()->getContainer());
        $car = $data['M2'];

        $typeArray = array();
        foreach($car->getTypeId() as $type){
            array_push($typeArray, array(
                'id' => $type->getId(),
                'type' => $type->getType()
            ));
        }

        $optionArray = array();
        foreach($car->getOptionId() as $option){
            array_push($optionArray, array(
                'id' => $option->getId(),
                'name' => $option->getName(),
                'description' => $option->getDescription()
            ));
        }

        $expected_array = [
            'id' => $car->getId(),
            'horsepower' => $car->getHorsepower(),
            'description' => $car->getDescription(),
            'daily_price' => $car->getDailyPrice(),
            'release_year' => $car->getReleaseYear(),
            'fuel' => [
                'id' => $car->getFuelId()->getId(),
                'fuel' => $car->getFuelId()->getFuel()
            ],
            'brand' => [
                'id' => $car->getBrandId()->getId(),
                'brand' => $car->getBrandId()->getBrand()
            ],
            'model' => [
                'id' => $car->getModelId()->getId(),
                'model' => $car->getModelId()->getModel()
            ],
            'color' => [
                'id' => $car->getColorId()->getId(),
                'color' => $car->getColorId()->getColor()
            ],
            'gearbox' => [
                'id' => $car->getGearboxId()->getId(),
                'gearbox' => $car->getGearboxId()->getGearbox()
            ],
            'types' => $typeArray,
            'options' => $optionArray,
            'office' => [
                'id' => $car->getOfficeId()->getId()
            ]
        ];

        $normalizer = $this->createMock(CarNormalizer::class);
        $normalizer->expects($this->once())
                   ->method('normalize')
                   ->willReturn($expected_array);

        $normalizer->normalize($car);
    }
}