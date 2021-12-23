<?php

namespace App\Normalizer;

use App\Entity\Rent;
use Symfony\Component\Serializer\Normalizer\CacheableSupportsMethodInterface;
use Symfony\Component\Serializer\Normalizer\ContextAwareNormalizerInterface;

class RentNormalizer implements ContextAwareNormalizerInterface, CacheableSupportsMethodInterface
{
    public function __construct(
        private OfficeNormalizer $officeNormalizer,
        private UserNormalizer $userNormalizer,
        private CarNormalizer $carNormalizer,
        private StatusNormalizer $statusNormalizer
    ) {}

    public function supportsNormalization($data, ?string $format = null, array $context = [])
    {
        return $data instanceof Rent;
    }

    public function normalize($object, ?string $format = null, array $context = [])
    {
        if(!$object instanceof Rent){
            throw new \InvalidArgumentException('Unexpected type for normalization, expected User, got '.get_class($object));
        }

        $rent = [
            'id' => $object->getId(),
            'price' => $object->getPrice(),
            'pickup_date' => $object->getPickupDate(),
            'return_date' => $object->getReturnDate(),
            'user' => $object->getUserId()->getId(),
            'car' => $object->getCarId()->getId(),
            'status' => $object->getStatusId()->getStatus(),
        ];

        if($format === 'extended') {
            $rent['pickup_office'] = $this->officeNormalizer->normalize($object->getPickupOfficeId(), 'extended');
            $rent['return_office'] = $this->officeNormalizer->normalize($object->getReturnOfficeId(), 'extended');
            $rent['user'] = $this->userNormalizer->normalize($object->getUserId(), 'extended');
            $rent['car'] = $this->carNormalizer->normalize($object->getCarId(), 'extended');
            $rent['status'] = $this->statusNormalizer->normalize($object->getStatusId());
        }

        return $rent;
    }

    public function hasCacheableSupportsMethod(): bool
    {
        return true;
    }
}