<?php

namespace App\Controller\Api;

use App\Repository\Components\TypeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ApiTypeController extends AbstractController
{

    #[Route('/api/search/type', name: 'api_type_search', methods: ['GET'])]
    public function getType(Request $request, TypeRepository $repository): JsonResponse {
        $array = array();
        $q = $request->query->get('q');

        $types = $repository->searchTypes($q);

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

    #[Route('/api/search/type_id', name: 'api_type_search_id', methods:['GET'])]
    public function getTypeId(Request $request, TypeRepository $repository) {
        $id = $request->query->get('id');
        $type = $repository->findOneBy(['id' => $id]);

        if(!$type) {
            return new JsonResponse([
                'message' => "Aucun type trouvé."
            ], 404);
        }

        return new JsonResponse([
            'data' => [
                'id' => $type->getId(),
                'color' => $type->getType(),
            ]
        ], 200);
    }
}