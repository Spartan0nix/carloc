<?php

namespace App\Normalizer;

use App\Entity\Rent;
use App\Entity\Status;
use Symfony\Component\Serializer\Normalizer\CacheableSupportsMethodInterface;
use Symfony\Component\Serializer\Normalizer\ContextAwareNormalizerInterface;

class StatusNormalizer implements ContextAwareNormalizerInterface, CacheableSupportsMethodInterface
{
    public function __construct(
        private OfficeNormalizer $officeNormalizer,
        private UserNormalizer $userNormalizer,
        private CarNormalizer $carNormalizer
    ) {}

    public function supportsNormalization($data, ?string $format = null, array $context = [])
    {
        return $data instanceof Status;
    }

    public function normalize($object, ?string $format = null, array $context = [])
    {
        if(!$object instanceof Status){
            throw new \InvalidArgumentException('Unexpected type for normalization, expected User, got '.get_class($object));
        }

        return [
            'id' => $object->getId(),
            'status' => $object->getStatus(),
        ];

    }

    public function hasCacheableSupportsMethod(): bool
    {
        return true;
    }
}