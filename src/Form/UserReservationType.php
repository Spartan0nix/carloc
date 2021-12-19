<?php

namespace App\Form;

use App\Form\Field\OfficeTypeField;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;

class UserReservationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('pickup_office', OfficeTypeField::class)
                ->add('return_office', OfficeTypeField::class, [
                    'required' => false
                ])
                ->add('pickup_date', DateType::class, [
                    'widget' => 'single_text'
                ])
                ->add('return_date', DateType::class, [
                    'widget' => 'single_text'
                ])
        ;
    }
}