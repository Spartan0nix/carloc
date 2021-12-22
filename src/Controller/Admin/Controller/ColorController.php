<?php

namespace App\Controller\Admin\Controller;

use App\Controller\Admin\Form\AdminColorType;
use App\Entity\Components\Color;
use App\Normalizer\ColorNormalizer;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Annotation\Route;

class ColorController extends CrudController
{
    protected string $index_route = 'admin_color_index';
    protected string $template = 'color';
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
        $this->entity = new Color();
        $this->formClass = AdminColorType::class;
        $this->request = $requestStack->getCurrentRequest();
    }

    #[Route('/admin/couleurs', name:'admin_color_index')]
    public function index(ColorNormalizer $normalizer) {      
        return $this->read($normalizer);
    }

    #[Route('/admin/couleurs/ajouter', name:'admin_color_add')]
    public function new() {
        return $this->create();
    }

    #[Route('/admin/couleurs/{id}/modifier', name:'admin_color_edit')]
    public function edit(string $id) {
        return $this->update($id);  
    }

    #[Route('/admin/couleurs/{id}/supprimer', name:'admin_color_delete')]
    public function remove(string $id) {
        try {
            return $this->delete($id);
        } catch (\Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException $exception) {
            $this->addFlash('error', 'Impossible de supprimer cette couleur, car elle est associée à un ou plusieurs véhicules.');
            return $this->redirectToRoute('admin_color_index');
        }
    }
}