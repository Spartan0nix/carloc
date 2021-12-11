<?php

namespace App\Controller\Admin\Form;

use App\Entity\Office;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class OfficeTypeField extends TextType implements DataTransformerInterface
{
    public function __construct(private EntityManagerInterface $em) {}

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addViewTransformer($this);
    }

    public function transform($office)
    {
        return null === $office ? '' : $office->getId();
    }

    public function reverseTransform($officeId)
    {
        if(!$officeId) {
            return null;
        }

        $office = $this->em->getRepository(Office::class)->find($officeId);

        return $office;
    }
}