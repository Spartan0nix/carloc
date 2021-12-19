<?php

namespace App\Form\Field;

use App\Entity\Components\Model;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class ModelTypeField extends TextType implements DataTransformerInterface
{
    public function __construct(private EntityManagerInterface $em) {}

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addModelTransformer($this);
    }

    public function transform($model): string
    {
        return null === $model ? '' : $model->getId();
    }

    public function reverseTransform($modelId): ?Model
    {
        if(!$modelId){
            return null;
        }

        $model = $this->em->getRepository(Model::class)->find($modelId);

        return $model;

    }
}