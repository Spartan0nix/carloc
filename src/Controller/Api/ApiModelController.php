<?php

namespace App\Controller\Api;

use App\Repository\Components\ModelRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ApiModelController extends AbstractController
{

    #[Route('/api/search/model', name: 'api_model_search', methods: ['GET'])]
    public function getModel(Request $request, ModelRepository $repository): JsonResponse {
        $array = array();
        $q = $request->query->get('q');

        $models = $repository->searchModels($q);

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