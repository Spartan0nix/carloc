<?php

namespace App\Controller\Car;

use App\Controller\Normalizer\CarNormalizer;
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
    /**
     * @var CarNormalizer
     */
    private $carNormalizer;

    public function __construct(CarRepository $carRepository, CarNormalizer $carNormalizer)
    {
        $this->repository = $carRepository;
        $this->carNormalizer = $carNormalizer;
    }

    
    #[Route('/locations', name: 'rent_list', methods: ["POST"])]
    public function listAvailableRent(Request $request) {

        if($request->request){
            $req = $request->request->all();
            $rentInfo = array();

            if(!$this->isCsrfTokenValid('token', $req['token'])){
                $this->addFlash("error","Erreur lors de la recherche.");
                return $this->redirectToRoute('rent_index');
            }

            if(!$req['return_office']){
                $req['return_office'] = $req['pickup_office'];
            }

            $rentInfo['pickup_office'] = $req['pickup_office'];
            $rentInfo['return_office'] = $req['return_office'];
            $rentInfo['start_date'] = $req['start_date'];
            $rentInfo['end_date'] = $req['end_date'];

            $cars = $this->repository->findAvailableCar($req['pickup_office']);

            $normalizeCar = array();
            foreach($cars as $car){
                array_push($normalizeCar, $this->carNormalizer->normalize($car));
            }

            dump($normalizeCar);
            dump($rentInfo);
        }
        
        return $this->render('rent/step_2/index.html.twig', [
            'rentInfo' => $rentInfo,
            'cars' => $normalizeCar
        ]);
    }
}