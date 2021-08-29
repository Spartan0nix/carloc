<?php 

namespace App\Form;

use App\Entity\Address\City;
use App\Entity\Address\Department;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserAddressType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('address', TextType::class, [
                'attr' => [
                    'placeholder' => 'Adresse'
                ]
            ])
            ->add('city_id', EntityType::class, [
                'class' => City::class,
                'choice_label' => 'name'
            ])
            ->add('department_id', EntityType::class, [
                'class' => Department::class,
                'choice_label' => 'name'
            ])
        ;
    }
}