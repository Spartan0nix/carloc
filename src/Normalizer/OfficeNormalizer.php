<?php

namespace App\Normalizer;

use App\Entity\Office;
use Symfony\Component\Serializer\Normalizer\CacheableSupportsMethodInterface;
use Symfony\Component\Serializer\Normalizer\ContextAwareNormalizerInterface;

class OfficeNormalizer implements ContextAwareNormalizerInterface, CacheableSupportsMethodInterface
{   

    public function __construct(private CityNormalizer $cityNormalizer, private DepartmentNormalizer $departmentNormalizer) {}

    public function supportsNormalization($data, ?string $format = null, array $context = [])
    {
        return $data instanceof Office;
    }

    public function normalize($object, ?string $format = null, array $context = [])
    {
        if(!$object instanceof Office){
            throw new \InvalidArgumentException('Unexpected type for normalization, expected User, got '.get_class($object));
        }

        $office = [
            'id' => $object->getId(),
            'street' => $object->getStreet(),
            'tel_number' => $object->getTelNumber(),
            'email' => $object->getEmail(),
        ];

        if($format === 'extended') {
            $office['city'] = $this->cityNormalizer->normalize($object->getCityId());
            $office['department'] = $this->departmentNormalizer->normalize($object->getDepartmentId());
        }

        return $office;
    }

    public function hasCacheableSupportsMethod(): bool
    {
        return true;
    }
}