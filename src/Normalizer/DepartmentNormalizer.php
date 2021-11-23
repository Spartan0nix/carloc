<?php

namespace App\Normalizer;

use App\Entity\Address\Department;
use Symfony\Component\Serializer\Normalizer\CacheableSupportsMethodInterface;
use Symfony\Component\Serializer\Normalizer\ContextAwareNormalizerInterface;

class DepartmentNormalizer implements ContextAwareNormalizerInterface, CacheableSupportsMethodInterface
{
    public function supportsNormalization($data, ?string $format = null, array $context = [])
    {
        return $data instanceof Department;
    }

    public function normalize($object, ?string $format = null, array $context = [])
    {
        if(!$object instanceof Department) {
            throw new \InvalidArgumentException('Unexpected type for normalization, expected User, got '.get_class($object));
        }

        return [
            'id' => $object->getId(),
            'name' => $object->getName(),
            'code' => $object->getCode(),
        ];
    }

    public function hasCacheableSupportsMethod(): bool
    {
        return true;
    }
}