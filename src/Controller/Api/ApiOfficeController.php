<?php

namespace App\Controller\Api;

use App\Repository\OfficeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ApiOfficeController extends AbstractController
{   

    #[Route('/api/search/office', name: 'api_office_search', methods: ['GET'])]
    public function getOffice(Request $request, OfficeRepository $repository): JsonResponse{
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

    #[Route('/api/search/office_id', name:'api_office_search_id', methods:['GET'])]
    public function getOfficeId(Request $request, OfficeRepository $repository) {
        $id = $request->query->get('id');
        $office = $repository->findOneBy(['id' => $id]);

        if(!$office) {
            return new JsonResponse([
                'message' => 'Aucune agence(s) trouvée(s).'
            ], 404);
        }

        return new JsonResponse([
            'data' => [
                'id' => $office->getId(),
                'street' => $office->getStreet(),
                'tel_number' => $office->getTelNumber(),
                'email' => $office->getEmail()
            ]
        ], 200);
    }
}