<?php

namespace App\Controller\Normalizer;

use App\Entity\Car;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CarNormalizer extends AbstractController
{

    public function normalize(Car $car){

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

        return array(
            'id' => $car->getId(),
            'horsepower' => $car->getHorsepower(),
            'description' => $car->getDescription(),
            'daily_price' => $car->getDailyPrice(),
            'release_year' => $car->getReleaseYear(),
            'fuel' => array(
                'id' => $car->getFuelId()->getId(),
                'fuel' => $car->getFuelId()->getFuel()
            ),
            'brand' => array(
                'id' => $car->getBrandId()->getId(),
                'brand' => $car->getBrandId()->getBrand()
            ),
            'model' => array(
                'id' => $car->getModelId()->getId(),
                'model' => $car->getModelId()->getModel()
            ),
            'color' => array(
                'id' => $car->getColorId()->getId(),
                'color' => $car->getColorId()->getColor()
            ),
            'gearbox' => array(
                'id' => $car->getGearboxId()->getId(),
                'gearbox' => $car->getGearboxId()->getGearbox()
            ),
            'types' => $typeArray,
            'options' => $optionArray,
            'office' => array(
                'id' => $car->getOfficeId()->getId()
            )
        );

    }
}