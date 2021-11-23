<?php

namespace App\Normalizer;

use App\Entity\User;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\Normalizer\CacheableSupportsMethodInterface;
use Symfony\Component\Serializer\Normalizer\ContextAwareNormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class UserNormalizer implements ContextAwareNormalizerInterface, CacheableSupportsMethodInterface
{
    public function __construct(
        private UrlGeneratorInterface $urlGenerator, 
        private ObjectNormalizer $normalizer,
        private CityNormalizer $cityNormalizer,
        private DepartmentNormalizer $departmentNormalizer
    ){}

    public function supportsNormalization($data, ?string $format = null, array $context = [])
    {
        return $data instanceof User;    
    }

    public function normalize($object, ?string $format = null, array $context = [])
    {
        if(!$object instanceof User) {
            throw new \InvalidArgumentException('Unexpected type for normalization, expected User, got '.get_class($object));
        }

            $user = [
                'id' => $object->getId(),
                'email' => $object->getEmail(),
                'roles' => $object->getRoles(),
                'last_name' => $object->getLastName(),
                'first_name' => $object->getFirstName(),
            ];

        if($format === 'extended') {
            $user['address'] = $object->getAddress();
            $user['city'] = $this->cityNormalizer->normalize($object->getCityId());
            $user['department'] = $this->departmentNormalizer->normalize($object->getDepartmentId());
        }
        
        return $user;
    }

    public function hasCacheableSupportsMethod(): bool
    {
        return true;
    }
}