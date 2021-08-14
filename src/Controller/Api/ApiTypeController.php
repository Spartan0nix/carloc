<?php

namespace App\Controller\Api;

use App\Repository\Components\TypeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ApiTypeController extends AbstractController
{
    /**
     * @var TypeRepository
     */
    private $repository;

    public function __construct(TypeRepository $repository)
    {
        $this->repository = $repository;
    }

    #[Route('/recherche/types', name: 'types_search', methods: ['GET'])]
    public function getTypes(Request $request): JsonResponse {
        $array = array();
        $q = $request->query->get('q');

        $types = $this->repository->searchTypes($q);

        if(!$types){
            return new JsonResponse(array(
                'message' =>  'Aucun véhicules de ce type trouvé.'
            ),404);
        }

        foreach($types as $type){
            array_push($array, array(
                'id' => $type->getId(),
                'type' => $type->getType()
            ));
        }

        return new JsonResponse(array(
            'data' => $array
        ),200);
    }
}