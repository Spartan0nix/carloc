<?php

namespace App\Controller\Api;

use App\Repository\Components\BrandRepository;
use App\Repository\Components\ColorRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ApiColorController extends AbstractController
{

    #[Route('/api/search/color', name: 'api_color_search', methods: ['GET'])]
    public function getColor(Request $request, ColorRepository $repository): JsonResponse {
        $array = array();
        $q = $request->query->get('q');

        $colors = $repository->searchColors($q);

        if(!$colors){
            return new JsonResponse(array(
                'message' =>  'Aucune couleur(s) trouvée(s)'
            ),404);
        }

        foreach($colors as $color){
            array_push($array, array(
                'id' => $color->getId(),
                'color' => $color->getColor()
            ));
        }

        return new JsonResponse(array(
            'data' => $array
        ),200);
    }

    #[Route('/api/search/color_id', name: 'api_color_search_id', methods:['GET'])]
    public function getColorId(Request $request, ColorRepository $repository) {
        $id = $request->query->get('id');
        $color = $repository->findOneBy(['id' => $id]);

        if(!$color) {
            return new JsonResponse([
                'message' => "Aucune coleur(s) trouvée(s)."
            ], 404);
        }

        return new JsonResponse([
            'data' => [
                'id' => $color->getId(),
                'color' => $color->getColor(),
            ]
        ], 200);
    }
}