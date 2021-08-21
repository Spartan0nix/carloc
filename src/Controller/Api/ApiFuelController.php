<?php

namespace App\Controller\Api;

use App\Repository\Components\FuelRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ApiFuelController extends AbstractController
{

    #[Route('/api/search/fuels', name: 'api_fuel_search', methods: ['GET'])]
    public function getFuels(Request $request, FuelRepository $repository): JsonResponse {
        $array = array();
        $q = $request->query->get('q');

        $fuels = $repository->searchFuels($q);

        if(!$fuels){
            return new JsonResponse(array(
                'message' =>  'Aucun véhicule utilisant cette énergie trouvée.'
            ),404);
        }

        foreach($fuels as $fuel){
            array_push($array, array(
                'id' => $fuel->getId(),
                'fuel' => $fuel->getFuel()
            ));
        }

        return new JsonResponse(array(
            'data' => $array
        ),200);
    }
}