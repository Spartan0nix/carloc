<?php

namespace App\Controller\Admin\Controller;

use App\Controller\Admin\Form\AdminTypeType;
use App\Entity\Components\Type;
use App\Normalizer\TypeNormalizer;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Annotation\Route;

class TypeController extends CrudController
{
    protected string $index_route = 'admin_type_index';
    protected string $template = 'type';
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
        $this->entity = new Type();
        $this->formClass = AdminTypeType::class;
        $this->request = $requestStack->getCurrentRequest();
    }

    #[Route('/admin/types', name:'admin_type_index')]
    public function index(TypeNormalizer $normalizer) {      
        return $this->read($normalizer);
    }

    #[Route('/admin/types/ajouter', name:'admin_type_add')]
    public function new() {
        return $this->create();
    }

    #[Route('/admin/types/{id}/modifier', name:'admin_type_edit')]
    public function edit(string $id) {
        return $this->update($id);  
    }

    #[Route('/admin/types/{id}/supprimer', name:'admin_type_delete')]
    public function remove(string $id) {
        try {
            return $this->delete($id);
        } catch (\Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException $exception) {
            $this->addFlash('error', 'Impossible de supprimer ce type, car il est associé à un ou plusieurs véhicules.');
            return $this->redirectToRoute('admin_type_index');
        }
    }
}