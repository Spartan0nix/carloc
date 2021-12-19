<?php

namespace App\Form\Field;

use App\Entity\Address\Department;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class DepartmentTypeField extends TextType implements DataTransformerInterface
{
    public function __construct(private EntityManagerInterface $em) {}

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addViewTransformer($this);
    }

    public function transform($department)
    {
        return null === $department ? '' : $department->getId();
    }

    public function reverseTransform($departmentId)
    {
        if(!$departmentId) {
            return null;
        }

        $department = $this->em->getRepository(Department::class)->find($departmentId);

        return $department;
    }
}