<?php

namespace App\Form\User;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class UserChangeInfo extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('last_name', TextType::class)
                ->add('first_name', TextType::class)
                ->add('email', EmailType::class)
                ->add('submit', SubmitType::class, [
                    'attr' => ['class' => 'btn'],
                    'label' => 'Confirmer'
                ])
        ;
    }
}