<?php

namespace App\Controller\Api;

use App\Repository\StatusRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ApiStatusController extends AbstractController
{   

    #[Route('/api/search/status', name: 'api_status_search', methods: ['GET'])]
    public function getOffice(Request $request, StatusRepository $repository): JsonResponse{
        $array = array();
        $q = $request->query->get('q');

        $status = $repository->searchStatus($q);

        if(!$status){
            return new JsonResponse(array(
                'message' =>  'Aucun Utilisateur(s) trouvé(s)'
            ),404);
        }

        foreach($status as $status){
            array_push($array, array(
                'id' => $status->getId(),
                'status' => $status->getStatus(),
            ));
        }

        return new JsonResponse(array(
            'data' => $array
        ),200);
    }

    #[Route('/api/search/status_id', name:'api_status_search_id', methods:['GET'])]
    public function getStatusId(Request $request, StatusRepository $repository) {
        $id = $request->query->get('id');
        $status = $repository->findOneBy(['id' => $id]);

        if(!$status) {
            return new JsonResponse([
                'message' => 'Aucun état(s) trouvé(s).'
            ], 404);
        }

        return new JsonResponse([
            'data' => [
                'id' => $status->getId(),
                'status' => $status->getStatus(),
            ]
        ], 200);
    }
}