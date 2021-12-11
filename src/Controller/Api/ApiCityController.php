<?php

namespace App\Controller\Api;

use App\Repository\Address\CityRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ApiCityController extends AbstractController
{
    #[Route('/api/search/city', name:'api_city_search', methods:['GET'])]
    public function getCity(Request $request, CityRepository $repository) {
        $q = $request->query->get('q');
        $required_department_id = $request->query->get('require') ? $request->query->get('require') : '';

        $cities = $repository->searchCities($q, $required_department_id);

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

    #[Route('/api/search/city_id', name:'api_city_search_id', methods:['GET'])]
    public function getCityId(Request $request, CityRepository $repository) {
        $id = $request->query->get('id');
        $city = $repository->findOneBy(['id' => $id]);

        if(!$city) {
            return new JsonResponse([
                'message' => 'Aucune ville(s) trouvée(s).'
            ], 404);
        }

        return new JsonResponse([
            'data' => [
                'id' => $city->getId(),
                'name' => $city->getName(),
                'code' => $city->getCode(),
            ]
        ], 200);
    }
}