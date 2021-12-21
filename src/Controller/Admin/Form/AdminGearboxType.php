<?php

namespace App\Controller\Admin\Form;

use App\Repository\Components\GearboxRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

class AdminGearboxType extends AbstractType
{

    public function __construct(private GearboxRepository $repository, private RequestStack $request){}
    function buildForm(FormBuilderInterface $builder, array $options)
    {
        // Get the last entity
        $gearbox = $this->repository->findBy([], ['id' => 'DESC']);
        $last_id = $gearbox[0]->getId();
        // Get the current url
        $current_url = $this->request->getCurrentRequest()->getRequestUri();

        // if a user when to edit the last element, the min number should be the last id and not last + 1
        if($current_url === "/admin/boite-vitesse/{$last_id}/modifier") {
            $builder->add('id', IntegerType::class, [
                'attr' => [
                    'min' => $last_id
                ]
            ]);
        } else {
            $builder->add('id', IntegerType::class, [
                'attr' => [
                    'min' => $last_id + 1
                ]
            ]);
        }
        
        $builder->add('gearbox', TextType::class)
                ->add('submit', SubmitType::class, [
                    'attr' => ['class' => 'btn'],
                    'label' => 'Confirmer'
                ])
        ;
    }
}