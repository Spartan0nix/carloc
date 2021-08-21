<?php

namespace App\Controller\Api;

use App\Repository\Components\BrandRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ApiBrandController extends AbstractController
{

    #[Route('/api/search/brands', name: 'api_brand_search', methods: ['GET'])]
    public function getBrands(Request $request, BrandRepository $repository): JsonResponse {
        $array = array();
        $q = $request->query->get('q');

        $brands = $repository->searchBrands($q);

        if(!$brands){
            return new JsonResponse(array(
                'message' =>  'Aucune marques trouvées'
            ),404);
        }

        foreach($brands as $brand){
            array_push($array, array(
                'id' => $brand->getId(),
                'brand' => $brand->getBrand()
            ));
        }

        return new JsonResponse(array(
            'data' => $array
        ),200);
    }
}