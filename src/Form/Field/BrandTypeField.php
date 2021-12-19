<?php

namespace App\Form\Field;

use App\Entity\Components\Brand;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class BrandTypeField extends TextType implements DataTransformerInterface
{
    public function __construct(private EntityManagerInterface $em) {}

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addModelTransformer($this);
    }

    public function transform($brand): string
    {
        return null === $brand ? '' : $brand->getId();
    }

    public function reverseTransform($brandId): ?Brand
    {
        if(!$brandId){
            return null;
        }

        $brand = $this->em->getRepository(Brand::class)->find($brandId);

        return $brand;

    }
}