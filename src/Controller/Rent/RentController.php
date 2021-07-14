<?php


namespace App\Controller\Rent;

use App\Controller\Normalizer\CarNormalizer;
use App\Repository\CarRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RentController extends AbstractController
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
    
    /**
     * Render the rent_step_1 page
     * @return void
     */
    #[Route('/location', name: 'rent_index')]
    public function index(){
        return $this->render('rent/step_1/index.html.twig');
    }

    /**
     * Render the rent_step_2
     * @param Request $request
     * @return void
     */
    #[Route('/locations', name: 'rent_list', methods: ["POST", "GET"])]
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

            $cars = $this->repository->findAvailableCar($rentInfo['pickup_office']);

            $normalizeCar = array();
            foreach($cars as $car){
                array_push($normalizeCar, $this->carNormalizer->normalize($car));
            }

            dump($rentInfo);
            dump($normalizeCar);
        }
        
        return $this->render('rent/step_2/index.html.twig', [
            'rentInfo' => json_encode($rentInfo),
            'cars' => $normalizeCar
        ]);
    }

    #[Route('/locations/filter', name:'rent_filter', methods: ["POST"])]
    public function filterAvailableRent(Request $request): Response {
        if($request->request) {
            $req = $request->request->all();
            $normalizeCar = array();

            $rentInfo = json_decode($req['rentInfo'], true);

            $cars = $this->repository->filterAvailableCar($rentInfo['pickup_office'], $req['brand_filter'], $req['model_filter'], $req['type_filter'], $req['fuel_filter'], $req['gearbox_filter']);

            if(!$cars) {
                $this->addFlash('warning', 'Aucun véhicules trouvés.');
                return $this->redirectToRoute('rent_index');
            }

            foreach($cars as $car) {
                array_push($normalizeCar, array(
                    'id' => $car->getId(),
                    'horsepower' => $car->getHorsepower(),
                    'daily_price' => $car->getDailyPrice(),
                    'brand' => array('brand' => $car->getBrandId()->getBrand()),
                    'modele' => array('modele' => $car->getModeleId()->getModele()),
                    'gearbox' => array('gearbox' => $car->getGearboxId()->getGearbox()),
                    'fuel' => array('fuel' => $car->getFuelId()->getFuel()),
                ));
            }

            dump($normalizeCar);
        }
        return $this->render('rent/step_2/index.html.twig', [
            'rentInfo' => json_encode($rentInfo),
            'cars' => $normalizeCar
        ]);
    }
}