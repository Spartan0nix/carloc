<?php

namespace App\Controller\Admin\Controller;

use App\Controller\Admin\Form\AdminOfficeType;
use App\Entity\Office;
use App\Normalizer\OfficeNormalizer;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Annotation\Route;

class OfficeController extends CrudController
{
    protected string $index_route = 'admin_office_index';
    protected string $template = 'office';
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
        $this->entity = new Office();
        $this->formClass = AdminOfficeType::class;
        $this->request = $requestStack->getCurrentRequest();
    }

    #[Route('/admin/offices', name:'admin_office_index')]
    public function index(OfficeNormalizer $normalizer) {      
        return $this->read($normalizer);
    }

    #[Route('/admin/offices/ajouter', name:'admin_office_add')]
    public function new() {
        return $this->create();
    }

    #[Route('/admin/offices/{id}/modifier', name:'admin_office_edit')]
    public function edit(string $id) {
        return $this->update($id);       
    }

    #[Route('/admin/offices/{id}/supprimer', name:'admin_office_delete')]
    public function remove(string $id) {
        return $this->delete($id);
    }
}