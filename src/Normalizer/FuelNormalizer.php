<?php

namespace App\Normalizer;

use App\Entity\Components\Fuel;
use Symfony\Component\Serializer\Normalizer\CacheableSupportsMethodInterface;
use Symfony\Component\Serializer\Normalizer\ContextAwareNormalizerInterface;

class FuelNormalizer implements ContextAwareNormalizerInterface, CacheableSupportsMethodInterface
{
    public function supportsNormalization($data, ?string $format = null, array $context = [])
    {
        return $data instanceof Fuel;
    }

    public function normalize($object, ?string $format = null, array $context = [])
    {
        if(!$object instanceof Fuel){
            throw new \InvalidArgumentException('Unexpected type for normalization, expected User, got '.get_class($object));
        }

        return [
            'id' => $object->getId(),
            'fuel' => $object->getFuel()
        ];
    }

    public function hasCacheableSupportsMethod(): bool
    {
        return true;
    }
}