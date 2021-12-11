<?php

namespace App\Controller\Api;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ApiUserController extends AbstractController
{   

    #[Route('/api/search/user', name: 'api_user_search', methods: ['GET'])]
    public function getOffice(Request $request, UserRepository $repository): JsonResponse{
        $array = array();
        $q = $request->query->get('q');

        $users = $repository->searchUsers($q);

        if(!$users){
            return new JsonResponse(array(
                'message' =>  'Aucun Utilisateur(s) trouvé(s)'
            ),404);
        }

        foreach($users as $user){
            array_push($array, array(
                'id' => $user->getId(),
                'email' => $user->getEmail(),
                'last_name' => $user->getLastName(),
                'fist_name' => $user->getFirstName()
            ));
        }

        return new JsonResponse(array(
            'data' => $array
        ),200);
    }

    #[Route('/api/search/user_id', name:'api_user_search_id', methods:['GET'])]
    public function getUserId(Request $request, UserRepository $repository) {
        $id = $request->query->get('id');
        $user = $repository->findOneBy(['id' => $id]);

        if(!$user) {
            return new JsonResponse([
                'message' => 'Aucun utilisateur(s) trouvé(s).'
            ], 404);
        }

        return new JsonResponse([
            'data' => [
                'id' => $user->getId(),
                'email' => $user->getEmail(),
                'last_name' => $user->getLastName(),
                'fist_name' => $user->getFirstName()
            ]
        ], 200);
    }
}