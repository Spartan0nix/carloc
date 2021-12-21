<?php

namespace App\Normalizer;

use App\Entity\Components\Gearbox;
use Symfony\Component\Serializer\Normalizer\CacheableSupportsMethodInterface;
use Symfony\Component\Serializer\Normalizer\ContextAwareNormalizerInterface;

class GearboxNormalizer implements ContextAwareNormalizerInterface, CacheableSupportsMethodInterface
{
    public function supportsNormalization($data, ?string $format = null, array $context = [])
    {
        return $data instanceof Gearbox;
    }

    public function normalize($object, ?string $format = null, array $context = [])
    {
        if(!$object instanceof Gearbox) {
            throw new \InvalidArgumentException('Unexpected type for normalization, expected City, got '.get_class($object));
        }

        return [
            'id' => $object->getId(),
            'gearbox' => $object->getGearbox(),
        ];
    }

    public function hasCacheableSupportsMethod(): bool
    {
        return true;
    }
}