<?php

namespace App\Controller\Api;

use App\Repository\Address\CityRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ApiCityController extends AbstractController
{
    #[Route('/api/search/cities', name:'api_city_search', methods:['GET'])]
    public function getCities(Request $request, CityRepository $repository) {
        $q = $request->query->get('q');
        $cities = $repository->searchCities($q);

        if(!$cities){
            return new JsonResponse([
                'message' => 'Aucune villes trouvées'
            ], 404);
        }

        $data = array();
        foreach($cities as $city){
            array_push($data, [
                'id' => $city->getId(),
                'name' => $city->getName(),
                'code' => $city->getCode()
            ]);
        }

        return new JsonResponse([
            'data' => $data
        ],200);
    }
}