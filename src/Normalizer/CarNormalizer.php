<?php

namespace App\Normalizer;

use App\Entity\Car;
use Symfony\Component\Serializer\Normalizer\CacheableSupportsMethodInterface;
use Symfony\Component\Serializer\Normalizer\ContextAwareNormalizerInterface;

class CarNormalizer implements ContextAwareNormalizerInterface, CacheableSupportsMethodInterface
{
    public function supportsNormalization($data, ?string $format = null, array $context = [])
    {
        return $data instanceof Car;
    }

    public function normalize($object, ?string $format = null, array $context = [])
    {

        if(!$object instanceof Car){
            throw new \InvalidArgumentException('Unexpected type for normalization, expected User, got '.get_class($object));
        }
        

        $car = [
            'id' => $object->getId(),
            'horsepower' => $object->getHorsepower(),
            'description' => $object->getDescription(),
            'daily_price' => $object->getDailyPrice(),
            'release_year' => $object->getReleaseYear(),
            'fuel' => [
                'id' => $object->getFuelId()->getId(),
                'fuel' => $object->getFuelId()->getFuel()
            ],
            'brand' => [
                'id' => $object->getBrandId()->getId(),
                'brand' => $object->getBrandId()->getBrand()
            ],
            'model' => [
                'id' => $object->getModelId()->getId(),
                'model' => $object->getModelId()->getModel()
            ],
            'color' => [
                'id' => $object->getColorId()->getId(),
                'color' => $object->getColorId()->getColor()
            ],
            'gearbox' => [
                'id' => $object->getGearboxId()->getId(),
                'gearbox' => $object->getGearboxId()->getGearbox()
            ],
        ];

        if($format === 'extended') {
            $typeArray = [];
            foreach($object->getTypeId() as $type){
                array_push($typeArray, [
                    'id' => $type->getId(),
                    'type' => $type->getType()
                ]);
            }
            $optionArray = [];
            foreach($object->getOptionId() as $option){
                array_push($optionArray, [
                    'id' => $option->getId(),
                    'name' => $option->getName(),
                    'description' => $option->getDescription()
                ]);
            }

            $car['office'] = [
                'id' => $object->getOfficeId()->getId(),
                'street' => $object->getOfficeId()->getStreet(),
                'tel_number' => $object->getOfficeId()->getTelNumber(),
                'email' => $object->getOfficeId()->getEmail(),
            ];
            $car['types'] = $typeArray;
            $car['options'] = $optionArray;
        }

        return $car;
    }

    public function hasCacheableSupportsMethod(): bool
    {
        return true;
    }
}