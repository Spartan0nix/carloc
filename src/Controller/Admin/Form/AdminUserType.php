<?php

namespace App\Controller\Admin\Form;

use App\Form\Field\CityTypeField;
use App\Form\Field\DepartmentTypeField;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class AdminUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('email', EmailType::class)
                ->add('roles', ChoiceType::class, [
                    'choices' => [
                        'ROLE_USER' => 'ROLE_USER',
                        'ROLE_ADMIN' => 'ROLE_ADMIN'
                    ],
                    'expanded' => true,
                    'multiple' => true,
                    'label' => false
                ])
                ->add('password', PasswordType::class)
                ->add('last_name', TextType::class)
                ->add('first_name', TextType::class)
                ->add('address', TextType::class)
                ->add('city_id', CityTypeField::class)
                ->add('department_id', DepartmentTypeField::class)
                ->add('submit', SubmitType::class, [
                    'attr' => ['class' => 'btn'],
                    'label' => 'Confirmer'
                ])
        ;
    }
}