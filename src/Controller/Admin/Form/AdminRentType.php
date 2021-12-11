<?php

namespace App\Controller\Admin\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

class AdminRentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('price', IntegerType::class)
                ->add('pickup_date', DateType::class)
                ->add('return_date', DateType::class)
                ->add('pickup_office_id', OfficeTypeField::class)
                ->add('return_office_id', OfficeTypeField::class)
                ->add('user_id', UserTypeField::class)
                ->add('car_id', CarTypeField::class)
                ->add('status_id', StatusTypeField::class)
                ->add('submit', SubmitType::class, [
                    'attr' => ['class' => 'btn'],
                    'label' => 'Confirmer'
                ])
        ;
    }
}