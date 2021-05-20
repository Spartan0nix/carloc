<?php

namespace App\Controller\Offices\Api;

use App\Repository\OfficesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ApiOfficesController extends AbstractController
{   
    /**
     * @var OfficesRepository
     */
    private $repository;
    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(OfficesRepository $repository,EntityManagerInterface $em)
    {
        $this->repository = $repository;
        $this->em = $em;
    }

    #[Route('/recherche/offices', name: 'offices_search', methods: ['GET'])]
    public function getOffices(Request $request): JsonResponse{
        $array = array();
        $q = $request->query->get('q');

        $offices = $this->repository->searchOffices($q);

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