<?php

namespace App\Controller;

use App\Event\BuildFilterEvent;
use App\Helper\BuildFilter;
use App\Helper\BuildRentInfo;
use App\Repository\CarRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
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
    #[Route('/voitures', name: 'car_list', methods: ["POST"])]
    public function listAvailableCar(Request $request, Session $session, BuildRentInfo $rent_info_helper) {
        $req = $request->request->all();

        if(!$req){
            return $this->redirectToRoute('office_list');
        }
        
        if(!$this->isCsrfTokenValid('token', $req['token'])){
            $session->getFlashBag()->add('error', 'Jeton invalide.');
            return $this->redirectToRoute('office_list');
        }

        $rentInfo = $session->get('rentInfo');

        if(!$rentInfo){
            $rentInfo = $rent_info_helper->build($req);
            $session->set('rentInfo', $rentInfo);
        } else {
            $diff = array_diff($rentInfo, $req);
            if(!empty($diff)){
                $rentInfo = $rent_info_helper->build($req);
                $session->set('rentInfo', $rentInfo);
            }
        }

        // if(isset($diff['pickup_office']) || isset($diff['return_office']) || isset($diff['start_date']) || isset($diff['end_date'])) {
        //     $rentInfo = $this->buildRentInfo($req);
        //     $session->set('rentInfo', $rentInfo);
        // }

        $normalizeCars = [];
        $cars = $this->repository->findAvailableCar($rentInfo['pickup_office']);

        foreach($cars as $car){
            array_push($normalizeCars, array(
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
            'cars' => $normalizeCars
        ]);
    }

    /**
     * Filter available car
     * @param Request $request
     * @return Response
     */
    #[Route('/voitures/filtre', name:'car_list_filter', methods: ["GET"])]
    public function filterAvailableCar(Request $request, BuildFilter $filter_builder, Session $session): Response {
        $req = $request->query->all();

        if(!$this->isCsrfTokenValid('token', $req['token'])){
            $session->getFlashBag()->add("error","Erreur lors de la recherche.");
            return $this->redirectToRoute('office_list');
        }

        $filterIds = array(
            'brand' => isset($req['brand_filter']) ? array_values($req['brand_filter']) : '',
            'model' => isset($req['model_filter']) ? array_values($req['model_filter']) : '',
            'type' => isset($req['type_filter']) ? array_values($req['type_filter']) : '',
            'fuel' => $req['fuel_filter'],
            'gearbox' => $req['gearbox_filter'],
        );

        $filters = $filter_builder->buildFilter($filterIds);
        $rentInfo = $session->get('rentInfo');

        $cars = $this->repository->filterAvailableCar($rentInfo['pickup_office'], $filterIds['brand'], $filterIds['model'], $filterIds['type'], $filterIds['fuel'], $filterIds['gearbox']);

        if(!$cars) {
            $session->getFlashBag()->add('warning', 'Aucun véhicules trouvés.');
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
     * @param String $id
     * @return void
     */
    #[Route('/voiture/{id}/details', name:'car_details', methods: ['GET'])]
    public function getCarDetails(String $id){
        $car = $this->repository->find($id);
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