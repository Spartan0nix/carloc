<?php

namespace App\Controller\Api;

use App\Repository\Components\GearboxRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ApiGearboxController extends AbstractController
{

    #[Route('/api/search/gearbox', name: 'api_gearbox_search', methods: ['GET'])]
    public function getGearbox(Request $request, GearboxRepository $repository): JsonResponse {
        $array = array();
        $q = $request->query->get('q');

        $gearboxs = $repository->searchGearboxs($q);

        if(!$gearboxs){
            return new JsonResponse(array(
                'message' =>  'Aucun véhicule utilisant ce type de boÎte de vitesse trouvé.'
            ),404);
        }

        foreach($gearboxs as $gearbox){
            array_push($array, array(
                'id' => $gearbox->getId(),
                'gearbox' => $gearbox->getGearbox()
            ));
        }

        return new JsonResponse(array(
            'data' => $array
        ),200);
    }
}