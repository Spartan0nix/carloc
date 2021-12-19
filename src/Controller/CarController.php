<?php

namespace App\Controller;

use App\Form\CarFilterType;
use App\Normalizer\CarNormalizer;
use App\Repository\CarRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class CarController extends AbstractController
{
    public function __construct(
        private CarRepository $repository,
        private SessionInterface $session,
        private CarNormalizer $normalizer
    ) {}

    /**
     * List available car
     * @param Request $request
     * @return void
     */
    #[Route('/voitures', name: 'car_list', methods: ["POST"])]
    public function listAvailableCar(Request $request) {
        $form = $this->createForm(CarFilterType::class);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            return $this->redirectToRoute('car_list_filter', $form->getData(), 307);
        }

        $req = $request->request->all();

        if(!isset($req['user_reservation']) || empty($req['user_reservation'])) {
            return $this->redirectToRoute('office_list');
        }
    
        $pickup_office = $req['user_reservation']['pickup_office'];
        $return_office = $req['user_reservation']['return_office'];
        if($return_office === '') {
            $return_office = $pickup_office;
        }

        $this->session->set('rent_info', [
            'pickup_office' => $pickup_office,
            'return_office' => $return_office,
            'pickup_date' => $req['user_reservation']['pickup_date'],
            'return_date' => $req['user_reservation']['return_date']
        ]);

        $cars = $this->repository->findAvailableCar($pickup_office);
        $normalize_cars = [];
        foreach($cars as $car) {
            array_push($normalize_cars, $this->normalizer->normalize($car));
        }
 
        return $this->render('rent/step_2/index.html.twig', [
            'cars' => $normalize_cars,
            'form' => $form->createView()
        ]);
    }

    /**
     * Filter available car
     * @param Request $request
     * @return Response
     */
    #[Route('/voitures/filtre', name:'car_list_filter', methods: ["POST"])]
    public function filterAvailableCar(Request $request): Response {
        $form = $this->createForm(CarFilterType::class);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){}

        $req = $request->request->all();
        if(!isset($req['car_filter']) || empty($req['car_filter'])) {
            return $this->redirectToRoute('car_list');
        }

        $rent_info = $this->session->get('rent_info');
        if(!isset($rent_info) || empty($rent_info)) {
            return $this->redirectToRoute('office_list');
        }

        $filter_data = $req['car_filter'];
        unset($filter_data['submit']);
        unset($filter_data['_token']);

        $cars = $this->repository->filterAvailableCar($rent_info['pickup_office'], $filter_data);

        if(!$cars) {
            return $this->render('rent/step_2/not_found.html.twig', [
                'form' => $form->createView()
            ]);
        }

        $normalize_cars = [];
        foreach($cars as $car) {
            array_push($normalize_cars, $this->normalizer->normalize($car));
        }

        return $this->render('rent/step_2/index.html.twig', [
            'cars' => $normalize_cars,
            'form' => $form->createView()
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

        if(!$car) {
            return $this->redirectToRoute('car_list');
        }
        
        $normalize_car = $this->normalizer->normalize($car);

        return $this->render('rent/step_3/index.html.twig', [
            'car' => $normalize_car
        ]);
    }
}