<?php

namespace App\Form;

use App\Entity\Components\Brand;
use App\Entity\Components\Fuel;
use App\Entity\Components\Model;
use App\Entity\Components\Type;
use App\Form\Field\GearboxTypeField;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

class CarFilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('brand_id', EntityType::class, [
                    'class' => Brand::class,
                    'expanded' => true,
                    'multiple' => true,
                    'required' => false
                ])
                ->add('model_id', EntityType::class, [
                    'class' => Model::class,
                    'expanded' => true,
                    'multiple' => true,
                    'required' => false
                ])
                ->add('type_id', EntityType::class, [
                    'class' => Type::class,
                    'expanded' => true,
                    'multiple' => true,
                    'required' => false
                ])
                ->add('fuel_id', EntityType::class, [
                    'class' => Fuel::class,
                    'expanded' => true,
                    'multiple' => true,
                    'required' => false
                ])
                ->add('gearbox_id', GearboxTypeField::class, [
                    'required' => false
                ])
                ->add('submit', SubmitType::class, [
                    'attr' => ['class' => 'btn'],
                    'label' => 'Confirmer'
                ])
        ;
    }
}