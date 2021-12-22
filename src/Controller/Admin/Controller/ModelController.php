<?php

namespace App\Controller\Admin\Controller;

use App\Controller\Admin\Form\AdminModelType;
use App\Entity\Components\Model;
use App\Normalizer\ModelNormalizer;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Annotation\Route;

class ModelController extends CrudController
{
    protected string $index_route = 'admin_model_index';
    protected string $template = 'model';
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
        $this->entity = new Model();
        $this->formClass = AdminModelType::class;
        $this->request = $requestStack->getCurrentRequest();
    }

    #[Route('/admin/modeles', name:'admin_model_index')]
    public function index(ModelNormalizer $normalizer) {      
        return $this->read($normalizer);
    }

    #[Route('/admin/modeles/ajouter', name:'admin_model_add')]
    public function new() {
        return $this->create();
    }

    #[Route('/admin/modeles/{id}/modifier', name:'admin_model_edit')]
    public function edit(string $id) {
        return $this->update($id);  
    }

    #[Route('/admin/modeles/{id}/supprimer', name:'admin_model_delete')]
    public function remove(string $id) {
        try {
            return $this->delete($id);
        } catch (\Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException $exception) {
            $this->addFlash('error', 'Impossible de supprimer ce modèle, car il est associé à un ou plusieurs véhicules.');
            return $this->redirectToRoute('admin_model_index');
        }
    }
}