<?php

namespace App\Controller\Api;

use App\Repository\CarRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ApiCarController extends AbstractController
{   

    #[Route('/api/search/car', name: 'api_car_search', methods: ['GET'])]
    public function getOffice(Request $request, CarRepository $repository): JsonResponse{
        $array = array();
        $q = $request->query->get('q');

        $cars = $repository->searchCars($q);

        if(!$cars){
            return new JsonResponse(array(
                'message' =>  'Aucun Utilisateur(s) trouvé(s)'
            ),404);
        }

        foreach($cars as $car){
            array_push($array, array(
                'id' => $car->getId(),
                'brand' => $car->getBrandId()->getBrand(),
                'model' => $car->getModelId()->getModel(),
            ));
        }

        return new JsonResponse(array(
            'data' => $array
        ),200);
    }

    #[Route('/api/search/car_id', name:'api_car_search_id', methods:['GET'])]
    public function getCarId(Request $request, CarRepository $repository) {
        $id = $request->query->get('id');
        $car = $repository->findOneBy(['id' => $id]);

        if(!$car) {
            return new JsonResponse([
                'message' => 'Aucun véhicule(s) trouvé(s).'
            ], 404);
        }

        return new JsonResponse([
            'data' => [
                'id' => $car->getId(),
                'brand' => $car->getBrandId()->getBrand(),
                'model' => $car->getModelId()->getModel(),
            ]
        ], 200);
    }
}