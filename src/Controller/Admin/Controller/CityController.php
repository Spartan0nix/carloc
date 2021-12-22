<?php

namespace App\Controller\Admin\Controller;

use App\Controller\Admin\Form\AdminCityType;
use App\Entity\Address\City;
use App\Normalizer\CityNormalizer;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Annotation\Route;

class CityController extends CrudController
{
    protected string $index_route = 'admin_city_index';
    protected string $template = 'city';
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
        $this->entity = new City();
        $this->formClass = AdminCityType::class;
        $this->request = $requestStack->getCurrentRequest();
    }

    #[Route('/admin/villes', name:'admin_city_index')]
    public function index(CityNormalizer $normalizer) {      
        return $this->read($normalizer);
    }

    #[Route('/admin/villes/ajouter', name:'admin_city_add')]
    public function new() {
        return $this->create();
    }

    #[Route('/admin/villes/{id}/modifier', name:'admin_city_edit')]
    public function edit(string $id) {
        return $this->update($id);  
    }

    #[Route('/admin/villes/{id}/supprimer', name:'admin_city_delete')]
    public function remove(string $id) {
        try {
            return $this->delete($id);
        } catch (\Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException $exception) {
            $this->addFlash('error', 'Impossible de supprimer cette ville, car elle est associée à un ou plusieurs utilisateurs/agences.');
            return $this->redirectToRoute('admin_city_index');
        }
    }
}
