<?php

namespace App\Form\Field;

use App\Entity\Components\Fuel;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class FuelTypeField extends TextType implements DataTransformerInterface
{
    public function __construct(private EntityManagerInterface $em) {}

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addModelTransformer($this);
    }

    public function transform($fuel): string
    {
        return null === $fuel ? '' : $fuel->getId();
    }

    public function reverseTransform($fuelId): ?Fuel
    {
        if(!$fuelId){
            return null;
        }

        $fuel = $this->em->getRepository(Fuel::class)->find($fuelId);

        return $fuel;

    }
}