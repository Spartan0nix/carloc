<?php

namespace App\Form\Field;

use App\Entity\Status;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class StatusTypeField extends TextType implements DataTransformerInterface
{
    public function __construct(private EntityManagerInterface $em) {}

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addViewTransformer($this);
    }

    public function transform($status)
    {
        return null === $status ? '' : $status->getId();
    }

    public function reverseTransform($statusId)
    {
        if(!$statusId) {
            return null;
        }

        $status = $this->em->getRepository(Status::class)->find($statusId);

        return $status;
    }
}