<?php

namespace App\Controller\Car;

use App\Repository\CarRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CarController extends AbstractController
{
    /**
     * @var CarRepository
     */
    private $repository;

    public function __construct(CarRepository $carRepository)
    {
        $this->repository = $carRepository;
    }

    
    #[Route('/locations', name: 'rent_list', methods: ["POST"])]
    public function listAvailableRent(Request $request) {

        if($request->request){
            $req = $request->request->all();
            if(!$this->isCsrfTokenValid('token', $req['token'])){
                $this->addFlash("error","Erreur lors de la recherche.");
                return $this->redirectToRoute('rent_index');
            }

            if(!$req['return_office']){
                $req['return_office'] = $req['pickup_office'];
            }

            dump($req);
        }
        
        return $this->render('rent/step_2/index.html.twig');
    }
}