<?php

namespace App\Controller\Admin\Controller;

use App\Controller\Admin\Form\AdminBrandType;
use App\Entity\Components\Brand;
use App\Normalizer\BrandNormalizer;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Annotation\Route;

class BrandController extends CrudController
{
    protected string $index_route = 'admin_brand_index';
    protected string $template = 'brand';
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
        $this->entity = new Brand();
        $this->formClass = AdminBrandType::class;
        $this->request = $requestStack->getCurrentRequest();
    }

    #[Route('/admin/marques', name:'admin_brand_index')]
    public function index(BrandNormalizer $normalizer) {      
        return $this->read($normalizer);
    }

    #[Route('/admin/marques/ajouter', name:'admin_brand_add')]
    public function new() {
        return $this->create();
    }

    #[Route('/admin/marques/{id}/modifier', name:'admin_brand_edit')]
    public function edit(string $id) {
        return $this->update($id);  
    }

    #[Route('/admin/marques/{id}/supprimer', name:'admin_brand_delete')]
    public function remove(string $id) {
        return $this->delete($id);
    }
}