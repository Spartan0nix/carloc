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

    private function createFilter($form_data) {
        return [
            'brand_id' => $form_data['brand_id']->map(fn($brand) => $brand->getId())->toArray(),
            'model_id' => $form_data['model_id']->map(fn($model) => $model->getId())->toArray(),
            'type_id' => $form_data['type_id']->map(fn($type) => $type->getId())->toArray(),
            'fuel_id' => $form_data['fuel_id']->map(fn($fuel) => $fuel->getId())->toArray(),
            'gearbox_id' => $form_data['gearbox_id'] != null ? $form_data['gearbox_id']->getId() : ''
        ];
    }

    /**
     * List available car
     * @param Request $request
     * @return void
     */
    #[Route('/voitures', name: 'car_list', methods: ["GET", "POST"])]
    public function listAvailableCar(Request $request) {
        $rent_info = $this->session->get('rent_info');
        if(!$rent_info) {
            return $this->redirectToRoute('office_list');
        }

        $form = $this->createForm(CarFilterType::class);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $cars = $this->repository->filterAvailableCar($rent_info['pickup_office'], $this->createFilter($form->getData()));
        } else {
            $cars = $this->repository->findAvailableCar($rent_info['pickup_office']);
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