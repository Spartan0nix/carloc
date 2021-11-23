<?php

namespace App\Normalizer;

use App\Entity\Components\Brand;
use Symfony\Component\Serializer\Normalizer\CacheableSupportsMethodInterface;
use Symfony\Component\Serializer\Normalizer\ContextAwareNormalizerInterface;

class BrandNormalizer implements ContextAwareNormalizerInterface, CacheableSupportsMethodInterface
{
    public function supportsNormalization($data, ?string $format = null, array $context = [])
    {
        return $data instanceof Brand;
    }

    public function normalize($object, ?string $format = null, array $context = [])
    {
        if(!$object instanceof Brand){
            throw new \InvalidArgumentException('Unexpected type for normalization, expected User, got '.get_class($object));
        }

        return [
            'id' => $object->getId(),
            'name' => $object->getBrand()
        ];
    }

    public function hasCacheableSupportsMethod(): bool
    {
        return true;
    }
}