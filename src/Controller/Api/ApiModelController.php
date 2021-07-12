<?php

namespace App\Controller\Api;

use App\Repository\Components\ModeleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ApiModelController extends AbstractController
{
    /**
     * @var ModeleRepository
     */
    private $repository;

    public function __construct(ModeleRepository $repository)
    {
        $this->repository = $repository;
    }

    #[Route('/recherche/models', name: 'models_search', methods: ['GET'])]
    public function getModels(Request $request): JsonResponse {
        $array = array();
        $q = $request->query->get('q');

        $models = $this->repository->searchModels($q);

        if(!$models){
            return new JsonResponse(array(
                'message' =>  'Aucun modéles trouvés'
            ),404);
        }

        foreach($models as $model){
            array_push($array, array(
                'id' => $model->getId(),
                'model' => $model->getModele()
            ));
        }

        return new JsonResponse(array(
            'data' => $array
        ),200);
    }
}