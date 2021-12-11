<?php

namespace App\Controller\Admin\Controller;

use App\Controller\Admin\Form\AdminRentType;
use App\Entity\Rent;
use App\Normalizer\RentNormalizer;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Annotation\Route;

class RentController extends CrudController
{
    protected string $index_route = 'admin_rent_index';
    protected string $template = 'rent';
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
        $this->entity = new Rent();
        $this->formClass = AdminRentType::class;
        $this->request = $requestStack->getCurrentRequest();
    }

    #[Route('/admin/locations', name:'admin_rent_index')]
    public function index(RentNormalizer $normalizer) {      
        return $this->read($normalizer);
    }

    #[Route('/admin/locations/ajouter', name:'admin_rent_add')]
    public function new() {
        return $this->create();
    }

    #[Route('/admin/locations/{id}/modifier', name:'admin_rent_edit')]
    public function edit(string $id) {
        return $this->update($id);  
    }

    #[Route('/admin/location/{id}/supprimer', name:'admin_rent_delete')]
    public function remove(string $id) {
        return $this->delete($id);
    }
}
