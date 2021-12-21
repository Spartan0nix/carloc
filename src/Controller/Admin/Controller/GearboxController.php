<?php

namespace App\Controller\Admin\Controller;

use App\Controller\Admin\Form\AdminGearboxType;
use App\Entity\Components\Gearbox;
use App\Normalizer\GearboxNormalizer;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Annotation\Route;

class GearboxController extends CrudController
{
    protected string $index_route = 'admin_gearbox_index';
    protected string $template = 'gearbox';
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
        $this->entity = new Gearbox();
        $this->formClass = AdminGearboxType::class;
        $this->request = $requestStack->getCurrentRequest();
    }

    #[Route('/admin/boite-vitesse', name:'admin_gearbox_index')]
    public function index(GearboxNormalizer $normalizer) {      
        return $this->read($normalizer);
    }

    #[Route('/admin/boite-vitesse/ajouter', name:'admin_gearbox_add')]
    public function new() {
        return $this->create();
    }

    #[Route('/admin/boite-vitesse/{id}/modifier', name:'admin_gearbox_edit')]
    public function edit(string $id) {
        return $this->update($id);  
    }

    #[Route('/admin/boite-vitesse/{id}/supprimer', name:'admin_gearbox_delete')]
    public function remove(string $id) {
        try {
            return $this->delete($id);
        } catch (\Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException $exception) {
            $this->addFlash('error', 'Impossible de supprimer ce type de boite, car celui-ci est associé à un ou plusieurs éléments.');
            return $this->redirectToRoute('admin_gearbox_index');
        }
    }
}
