<?php

namespace App\Controller\Admin\Controller;

use App\Controller\Admin\Form\AdminDepartmentType;
use App\Entity\Address\Department;
use App\Normalizer\DepartmentNormalizer;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Annotation\Route;

class DepartmentController extends CrudController
{
    protected string $index_route = 'admin_department_index';
    protected string $template = 'department';
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
        $this->entity = new Department();
        $this->formClass = AdminDepartmentType::class;
        $this->request = $requestStack->getCurrentRequest();
    }

    #[Route('/admin/departement', name:'admin_department_index')]
    public function index(DepartmentNormalizer $normalizer) {      
        return $this->read($normalizer);
    }

    #[Route('/admin/departement/ajouter', name:'admin_department_add')]
    public function new() {
        return $this->create();
    }

    #[Route('/admin/departement/{id}/modifier', name:'admin_department_edit')]
    public function edit(string $id) {
        return $this->update($id);  
    }

    #[Route('/admin/departement/{id}/supprimer', name:'admin_department_delete')]
    public function remove(string $id) {
        try {
            return $this->delete($id);
        } catch (\Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException $exception) {
            $this->addFlash('error', 'Impossible de supprimer ce département, car il est associé à un ou plusieurs utilisateurs/agences/villes.');
            return $this->redirectToRoute('admin_department_index');
        }
    }
}
