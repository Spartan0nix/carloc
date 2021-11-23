<?php

namespace App\Controller\Api;

use App\Repository\Address\DepartmentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ApiDepartmentController extends AbstractController
{
    #[Route('/api/search/department', name:'api_department_search', methods:['GET'])]
    public function getDepartment(Request $request, DepartmentRepository $repository) {
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

    #[Route('/api/search/department_id', name: 'api_department_search_id', methods:['GET'])]
    public function getDepartmentId(Request $request, DepartmentRepository $repository) {
        $id = $request->query->get('id');
        $departement = $repository->findOneBy(['id' => $id]);

        if(!$departement) {
            return new JsonResponse([
                'message' => "Aucun département avec cet id."
            ], 404);
        }

        return new JsonResponse([
            'department' => [
                'id' => $departement->getId(),
                'name' => $departement->getName(),
                'code' => $departement->getCode()
            ]
        ], 200);
    }
}