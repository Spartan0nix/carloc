<?php

namespace App\Normalizer;

use App\Entity\Components\Color;
use Symfony\Component\Serializer\Normalizer\CacheableSupportsMethodInterface;
use Symfony\Component\Serializer\Normalizer\ContextAwareNormalizerInterface;

class ColorNormalizer implements ContextAwareNormalizerInterface, CacheableSupportsMethodInterface
{
    public function supportsNormalization($data, ?string $format = null, array $context = [])
    {
        return $data instanceof Color;
    }

    public function normalize($object, ?string $format = null, array $context = [])
    {
        if(!$object instanceof Color){
            throw new \InvalidArgumentException('Unexpected type for normalization, expected User, got '.get_class($object));
        }

        return [
            'id' => $object->getId(),
            'color' => $object->getColor()
        ];
    }

    public function hasCacheableSupportsMethod(): bool
    {
        return true;
    }
}