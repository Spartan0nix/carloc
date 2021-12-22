<?php

namespace App\Normalizer;

use App\Entity\Components\Brand;
use App\Entity\Components\Type;
use Symfony\Component\Serializer\Normalizer\CacheableSupportsMethodInterface;
use Symfony\Component\Serializer\Normalizer\ContextAwareNormalizerInterface;

class TypeNormalizer implements ContextAwareNormalizerInterface, CacheableSupportsMethodInterface
{
    public function supportsNormalization($data, ?string $format = null, array $context = [])
    {
        return $data instanceof Type;
    }

    public function normalize($object, ?string $format = null, array $context = [])
    {
        if(!$object instanceof Type){
            throw new \InvalidArgumentException('Unexpected type for normalization, expected User, got '.get_class($object));
        }

        return [
            'id' => $object->getId(),
            'type' => $object->getType()
        ];
    }

    public function hasCacheableSupportsMethod(): bool
    {
        return true;
    }
}