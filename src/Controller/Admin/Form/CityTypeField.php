<?php

namespace App\Controller\Admin\Form;

use App\Entity\Address\City;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class CityTypeField extends TextType implements DataTransformerInterface
{
    public function __construct(private EntityManagerInterface $em) {}

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addModelTransformer($this);
    }

    public function transform($city): string
    {
        return null === $city ? '' : $city->getId();
    }

    public function reverseTransform($cityId): ?City
    {
        if(!$cityId){
            return null;
        }

        $city = $this->em->getRepository(City::class)->find($cityId);

        return $city;

    }
}