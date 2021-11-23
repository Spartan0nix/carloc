<?php

namespace App\Controller\Admin\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class AdminOfficeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('street', TextType::class)
                ->add('tel_number', TextType::class)
                ->add('email', EmailType::class)
                ->add('city_id', CityTypeField::class)
                ->add('department_id', DepartmentTypeField::class)
                ->add('submit', SubmitType::class, [
                    'attr' => ['class' => 'btn'],
                    'label' => 'Confirmer'
                ])
        ;
    }
}