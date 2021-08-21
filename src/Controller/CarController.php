<?php

namespace App\Controller;

use App\Event\BuildFilterEvent;
use App\Repository\CarRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class CarController extends AbstractController
{
    private CarRepository $repository;

    public function __construct(CarRepository $repository)
    {
        $this->repository = $repository;
    }
    /**
     * List available car
     * @param Request $request
     * @return void
     */
    #[Route('/voiture/list', name: 'car_list', methods: ["POST", "GET"])]
    public function listAvailableCar(Request $request, SessionInterface $session) {
        $req = $request->request->all();

        if(!$req){
            return $this->redirectToRoute('office_list');
        }

        if(!$this->isCsrfTokenValid('token', $req['token'])){
            $this->addFlash('error','Erreur lors de la recherche.');
            return $this->redirectToRoute('office_list');
        }

        $rentInfo = $session->get('rentInfo');
        
        if(!$rentInfo || $rentInfo['pickup_office'] != $req['pickup_office']){
            if(!$req['return_office']){
                $req['return_office'] = $req['pickup_office'];
            }
            
            $rentInfo = array();
            $rentInfo['pickup_office'] = $req['pickup_office'];
            $rentInfo['return_office'] = $req['return_office'];
            $rentInfo['start_date'] = $req['start_date'];
            $rentInfo['end_date'] = $req['end_date'];

            $session->set('rentInfo', $rentInfo);
        }

        $normalizeCar = array();
        $cars = $this->repository->findAvailableCar($rentInfo['pickup_office']);

        foreach($cars as $car){
            array_push($normalizeCar, array(
                'id' => $car->getId(),
                'horsepower' => $car->getHorsepower(),
                'daily_price' => $car->getDailyPrice(),
                'brand' => $car->getBrandId()->getBrand(),
                'model' => $car->getModelId()->getModel(),
                'gearbox' => $car->getGearboxId()->getGearbox(),
                'fuel' => $car->getFuelId()->getFuel(),
            ));
        }

        return $this->render('rent/step_2/index.html.twig', [
            'cars' => $normalizeCar
        ]);
    }

    /**
     * Filter available car
     * @param Request $request
     * @return Response
     */
    #[Route('/voiture/list/filter', name:'car_list_filter', methods: ["GET"])]
    public function filterAvailableCar(Request $request, BuildFilterEvent $event, SessionInterface $session): Response {
        $req = $request->query->all();

        if(!$this->isCsrfTokenValid('token', $req['token'])){
            $this->addFlash("error","Erreur lors de la recherche.");
            return $this->redirectToRoute('office_list');
        }

        $filterIds = array(
            'brand' => isset($req['brand_filter']) ? array_values($req['brand_filter']) : '',
            'model' => isset($req['model_filter']) ? array_values($req['model_filter']) : '',
            'type' => isset($req['type_filter']) ? array_values($req['type_filter']) : '',
            'fuel' => $req['fuel_filter'],
            'gearbox' => $req['gearbox_filter'],
        );

        $filters = $event->buildFilter($filterIds);
        $rentInfo = $session->get('rentInfo');

        $cars = $this->repository->filterAvailableCar($rentInfo['pickup_office'], $filterIds['brand'], $filterIds['model'], $filterIds['type'], $filterIds['fuel'], $filterIds['gearbox']);

        if(!$cars) {
            $this->addFlash('warning', 'Aucun véhicules trouvés.');
            return $this->render('rent/step_2/notFound.html.twig');
        }

        $normalizeCar = array();

        foreach($cars as $car) {
            array_push($normalizeCar, array(
                'id' => $car->getId(),
                'horsepower' => $car->getHorsepower(),
                'daily_price' => $car->getDailyPrice(),
                'brand' => array('brand' => $car->getBrandId()->getBrand()),
                'model' => array('model' => $car->getModelId()->getModel()),
                'gearbox' => array('gearbox' => $car->getGearboxId()->getGearbox()),
                'fuel' => array('fuel' => $car->getFuelId()->getFuel()),
            ));
        }

        return $this->render('rent/step_2/index.html.twig', [
            'cars' => $normalizeCar,
            'filter' => $filters
        ]);
    }

    /**
     * Render the rent_step_3
     * @param String $uid
     * @return void
     */
    #[Route('/voiture/{uid}/details', name:'car_details', methods: ['GET'])]
    public function getCarDetails(String $uid){
        $car = $this->repository->find($uid);
        $normalizeCar = [
            'id' => $car->getId(),
            'horsepower' => $car->getHorsepower(),
            'daily_price' => $car->getDailyPrice(),
            'description' => $car->getDescription(),
            'release_year' => $car->getReleaseYear(),
            'brand' => $car->getBrandId()->getBrand(),
            'model' => $car->getModelId()->getModel(),
            'gearbox' => $car->getGearboxId()->getGearbox(),
            'fuel' => $car->getFuelId()->getFuel(),
            'color' => $car->getColorId()->getColor(),
        ];

        return $this->render('rent/step_3/index.html.twig', [
            'car' => $normalizeCar
        ]);
    }
}