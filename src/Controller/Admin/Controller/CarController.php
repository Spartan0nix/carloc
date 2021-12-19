<?php

namespace App\Controller\Admin\Controller;

use App\Controller\Admin\Form\AdminCarType;
use App\Entity\Car;
use App\Normalizer\CarNormalizer;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Annotation\Route;

class CarController extends CrudController
{
    protected string $index_route = 'admin_car_index';
    protected string $template = 'car';
    protected EntityManagerInterface $em;
    protected object $entity;
    protected string $formClass;
    protected Request $request;

    public function __construct(
        EntityManagerInterface $em, 
        RequestStack $requestStack,
    )
    {
        $this->em = $em;
        $this->entity = new Car();
        $this->formClass = AdminCarType::class;
        $this->request = $requestStack->getCurrentRequest();
    }

    #[Route('/admin/véhicules', name:'admin_car_index')]
    public function index(CarNormalizer $normalizer) {      
        return $this->read($normalizer);
    }

    #[Route('/admin/véhicules/ajouter', name:'admin_car_add')]
    public function new() {
        return $this->create();
    }

    #[Route('/admin/véhicules/{id}/modifier', name:'admin_car_edit')]
    public function edit(string $id) {
        return $this->update($id);  
    }

    #[Route('/admin/véhicules/{id}/supprimer', name:'admin_car_delete')]
    public function remove(string $id) {
        return $this->delete($id);
    }
}