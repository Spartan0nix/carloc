<?php

namespace App\Form\Field;

use App\Entity\Components\Color;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ColorTypeField extends TextType implements DataTransformerInterface
{
    public function __construct(private EntityManagerInterface $em) {}

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addModelTransformer($this);
    }

    public function transform($color): string
    {
        return null === $color ? '' : $color->getId();
    }

    public function reverseTransform($colorId): ?Color
    {
        if(!$colorId){
            return null;
        }

        $color = $this->em->getRepository(Color::class)->find($colorId);

        return $color;

    }
}