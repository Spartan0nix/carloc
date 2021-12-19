<?php

namespace App\Form\Field;

use App\Entity\Car;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class CarTypeField extends TextType implements DataTransformerInterface
{
    public function __construct(private EntityManagerInterface $em) {}

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addViewTransformer($this);
    }

    public function transform($car)
    {
        return null === $car ? '' : $car->getId();
    }

    public function reverseTransform($carId)
    {
        if(!$carId) {
            return null;
        }

        $car = $this->em->getRepository(Car::class)->find($carId);

        return $car;
    }
}