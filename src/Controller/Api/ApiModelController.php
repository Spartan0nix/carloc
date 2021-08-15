<?php

namespace App\Controller\Api;

use App\Repository\Components\ModelRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ApiModelController extends AbstractController
{
    /**
     * @var ModelRepository
     */
    private $repository;

    public function __construct(ModelRepository $repository)
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
                'model' => $model->getModel()
            ));
        }

        return new JsonResponse(array(
            'data' => $array
        ),200);
    }
}