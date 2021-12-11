<?php

namespace App\Normalizer;

use App\Entity\Address\City;
use Symfony\Component\Serializer\Normalizer\CacheableSupportsMethodInterface;
use Symfony\Component\Serializer\Normalizer\ContextAwareNormalizerInterface;

class CityNormalizer implements ContextAwareNormalizerInterface, CacheableSupportsMethodInterface
{
    public function __construct(private DepartmentNormalizer $departmentNormalizer) {}

    public function supportsNormalization($data, ?string $format = null, array $context = [])
    {
        return $data instanceof City;
    }

    public function normalize($object, ?string $format = null, array $context = [])
    {
        if(!$object instanceof City) {
            throw new \InvalidArgumentException('Unexpected type for normalization, expected City, got '.get_class($object));
        }

        $city = [
            'id' => $object->getId(),
            'name' => $object->getName(),
            'code' => $object->getCode(),
        ];

        if($format === 'extended') {
            $city['department'] = $this->departmentNormalizer->normalize($object->getDepartmentId());
        }

        return $city;
    }

    public function hasCacheableSupportsMethod(): bool
    {
        return true;
    }
}