<?php

namespace App\Controller\Admin\Form;

use App\Entity\Components\Type;
use App\Form\Field\BrandTypeField;
use App\Form\Field\ColorTypeField;
use App\Form\Field\FuelTypeField;
use App\Form\Field\GearboxTypeField;
use App\Form\Field\ModelTypeField;
use App\Form\Field\OfficeTypeField;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;

class AdminCarType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('horsepower', IntegerType::class)
                ->add('daily_price', IntegerType::class)
                ->add('release_year', IntegerType::class)
                ->add('description', TextareaType::class)
                ->add('brand_id', BrandTypeField::class)
                ->add('model_id', ModelTypeField::class)
                ->add('fuel_id', FuelTypeField::class)
                ->add('gearbox_id', GearboxTypeField::class)
                ->add('color_id', ColorTypeField::class)
                ->add('type_id', EntityType::class, [
                    'class' => Type::class,
                    'expanded' => true,
                    'multiple' => true,
                ])
                // ->add('option_id', StatusTypeField::class)
                // ->add('image_id', StatusTypeField::class)
                ->add('office_id', OfficeTypeField::class)
                ->add('submit', SubmitType::class, [
                    'attr' => ['class' => 'btn'],
                    'label' => 'Confirmer'
                ])
        ;
    }
}