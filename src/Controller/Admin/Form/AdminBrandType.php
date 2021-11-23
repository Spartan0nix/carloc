<?php

namespace App\Controller\Admin\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class AdminBrandType extends AbstractType
{
    function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('brand', TextType::class)
                ->add('submit', SubmitType::class, [
                    'attr' => ['class' => 'btn'],
                    'label' => 'Confirmer'
                ])
        ;
    }
}