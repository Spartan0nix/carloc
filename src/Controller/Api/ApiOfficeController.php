<?php

namespace App\Controller\Api;

use App\Repository\OfficeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ApiOfficeController extends AbstractController
{   

    #[Route('/api/search/offices', name: 'api_office_search', methods: ['GET'])]
    public function getOffices(Request $request, OfficeRepository $repository): JsonResponse{
        $array = array();
        $q = $request->query->get('q');

        $offices = $repository->searchOffices($q);

        if(!$offices){
            return new JsonResponse(array(
                'message' =>  'Aucune agence(s) trouvée(s)'
            ),404);
        }

        foreach($offices as $office){
            array_push($array, array(
                'id' => $office->getId(),
                'street' => $office->getStreet(),
                'tel_number' => $office->getTelNumber(),
                'email' => $office->getEmail()
            ));
        }

        return new JsonResponse(array(
            'data' => $array
        ),200);

    }
}