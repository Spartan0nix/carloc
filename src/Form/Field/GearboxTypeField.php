<?php

namespace App\Form\Field;

use App\Entity\Components\Gearbox;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class GearboxTypeField extends TextType implements DataTransformerInterface
{
    public function __construct(private EntityManagerInterface $em) {}

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addModelTransformer($this);
    }

    public function transform($gearbox): string
    {
        return null === $gearbox ? '' : $gearbox->getId();
    }

    public function reverseTransform($gearboxId): ?Gearbox
    {
        if(!$gearboxId){
            return null;
        }

        $gearbox = $this->em->getRepository(Gearbox::class)->find($gearboxId);

        return $gearbox;

    }
}