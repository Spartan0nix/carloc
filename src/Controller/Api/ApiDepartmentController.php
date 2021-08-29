<?php

namespace App\Controller\Api;

use App\Repository\Address\DepartmentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ApiDepartmentController extends AbstractController
{
    #[Route('/api/search/departments', name:'api_department_search', methods:['GET'])]
    public function getDepartments(Request $request, DepartmentRepository $repository) {
        $q = $request->query->get('q');
        $departments = $repository->searchDepartments($q);

        if(!$departments) {
            return new JsonResponse([
                'message' => 'Aucun départements trouvés'
            ], 404);
        }

        $data = [];
        foreach($departments as $department){
            array_push($data, [
                'id' => $department->getId(),
                'name' => $department->getName(),
                'code' => $department->getCode(),
            ]);
        }

        return new JsonResponse([
            'data' => $data
        ],200);
    }
}