<?php

namespace App\Controller\Api;

use App\Repository\Components\FuelRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ApiFuelController extends AbstractController
{
    /**
     * @var FuelRepository
     */
    private $repository;

    public function __construct(FuelRepository $repository)
    {
        $this->repository = $repository;
    }

    #[Route('/recherche/fuels', name: 'fuels_search', methods: ['GET'])]
    public function getFuels(Request $request): JsonResponse {
        $array = array();
        $q = $request->query->get('q');

        $fuels = $this->repository->searchFuels($q);

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