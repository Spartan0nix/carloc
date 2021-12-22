<?php

namespace App\Normalizer;

use App\Entity\Components\Model;
use Symfony\Component\Serializer\Normalizer\CacheableSupportsMethodInterface;
use Symfony\Component\Serializer\Normalizer\ContextAwareNormalizerInterface;

class ModelNormalizer implements ContextAwareNormalizerInterface, CacheableSupportsMethodInterface
{
    public function supportsNormalization($data, ?string $format = null, array $context = [])
    {
        return $data instanceof Model;
    }

    public function normalize($object, ?string $format = null, array $context = [])
    {
        if(!$object instanceof Model){
            throw new \InvalidArgumentException('Unexpected type for normalization, expected User, got '.get_class($object));
        }

        return [
            'id' => $object->getId(),
            'model' => $object->getModel()
        ];
    }

    public function hasCacheableSupportsMethod(): bool
    {
        return true;
    }
}