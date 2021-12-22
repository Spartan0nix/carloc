<?php

namespace App\Controller\Admin\Controller;

use App\Controller\Admin\Form\AdminFuelType;
use App\Entity\Components\Fuel;
use App\Normalizer\FuelNormalizer;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Annotation\Route;

class FuelController extends CrudController
{
    protected string $index_route = 'admin_fuel_index';
    protected string $template = 'fuel';
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
        $this->entity = new Fuel();
        $this->formClass = AdminFuelType::class;
        $this->request = $requestStack->getCurrentRequest();
    }

    #[Route('/admin/energies', name:'admin_fuel_index')]
    public function index(FuelNormalizer $normalizer) {      
        return $this->read($normalizer);
    }

    #[Route('/admin/energies/ajouter', name:'admin_fuel_add')]
    public function new() {
        return $this->create();
    }

    #[Route('/admin/energies/{id}/modifier', name:'admin_fuel_edit')]
    public function edit(string $id) {
        return $this->update($id);  
    }

    #[Route('/admin/energies/{id}/supprimer', name:'admin_fuel_delete')]
    public function remove(string $id) {
        try {
            return $this->delete($id);
        } catch (\Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException $exception) {
            $this->addFlash('error', 'Impossible de supprimer cette energie, car elle est associée à un ou plusieurs véhicules.');
            return $this->redirectToRoute('admin_fuel_index');
        }
    }
}