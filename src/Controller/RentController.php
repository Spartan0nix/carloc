<?php


namespace App\Controller;

use App\Controller\Normalizer\CarNormalizer;
use App\Entity\Components\Brand;
use App\Entity\Components\Fuel;
use App\Entity\Components\Gearbox;
use App\Entity\Components\Modele;
use App\Entity\Components\Type;
use App\Repository\CarRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
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
    /**
     * @var Request
     */
    private $requestStack;

    public function __construct(CarRepository $carRepository, CarNormalizer $carNormalizer, RequestStack $requestStack)
    {
        $this->repository = $carRepository;
        $this->carNormalizer = $carNormalizer;
        $this->requestStack = $requestStack;
        
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
        if($request->request->all() && !$request->query->all()){
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

            $normalizeCar = array();

            $cars = $this->repository->findAvailableCar($rentInfo['pickup_office']);

            foreach($cars as $car){
                array_push($normalizeCar, $this->carNormalizer->normalize($car));
            }

            $session = $this->requestStack->getSession();
            $session->set('rentInfo', $rentInfo);

            return $this->render('rent/step_2/index.html.twig', [
                'rentInfo' => json_encode($rentInfo),
                'cars' => $normalizeCar
            ]);
        }
        if($request->query){
            $req = $request->query->all();
            /**
             * If the request is a redirect from another route
             */
            if(isset($req['redirect'])) {
                $req = $req['redirect'];

                if(!$this->isCsrfTokenValid('token', $req['token'])){
                    $this->addFlash("error","Erreur lors de la recherche.");
                    return $this->redirectToRoute('rent_index');
                }

                if(isset($req['filter'])) {

                    if($req['filter']['brand'] != ''){
                        $brands = $this->getDoctrine()->getRepository(Brand::class)->findBy([ 'id' => $req['filter']['brand']]);
                        $req['filter']['brand'] = array();

                        foreach($brands as $brand) {
                            array_push($req['filter']['brand'], array(
                                'id' => $brand->getId(),
                                'brand' => $brand->getBrand()
                            ));
                        }
                        $req['filter']['brand'] = json_encode($req['filter']['brand']);
                    }
                    if($req['filter']['model'] != ''){
                        $models = $this->getDoctrine()->getRepository(Modele::class)->findBy([ 'id' => $req['filter']['model']]);
                        $req['filter']['model'] = array();

                        foreach($models as $model) {
                            array_push($req['filter']['model'], array(
                                'id' => $model->getId(),
                                'model' => $model->getModele()
                            ));
                        }
                        $req['filter']['model'] = json_encode($req['filter']['model']);
                    }
                    if($req['filter']['type'] != ''){
                        $types = $this->getDoctrine()->getRepository(Type::class)->findBy([ 'id' => $req['filter']['type']]);
                        $req['filter']['type'] = array();

                        foreach($types as $type) {
                            array_push($req['filter']['type'], array(
                                'id' => $type->getId(),
                                'type' => $type->getType()
                            ));
                        }
                        $req['filter']['type'] = json_encode($req['filter']['type']);
                    }
                    if($req['filter']['fuel'] != ''){
                        $fuel = $this->getDoctrine()->getRepository(Fuel::class)->findOneBy([ 'id' => $req['filter']['fuel']]);
                        $req['filter']['fuel'] = array();

                        array_push($req['filter']['fuel'], json_encode(array(
                            'id' => $fuel->getId(),
                            'fuel' => $fuel->getFuel()
                        )));
                    }
                    if($req['filter']['gearbox'] != ''){
                        $gearbox = $this->getDoctrine()->getRepository(Gearbox::class)->findOneBy([ 'id' => $req['filter']['gearbox']]);
                        $req['filter']['gearbox'] = array();

                        array_push($req['filter']['gearbox'], json_encode(array(
                            'id' => $gearbox->getId(),
                            'gearbox' => $gearbox->getGearbox()
                        )));
                    }
                }
                /**
                 * If no cars has been found
                 */
                if(isset($req['status'])){
                    return $this->render('rent/step_2/index.html.twig', [
                        'rentInfo' => json_encode($req['rentInfo']),
                        'cars' => '',
                        'filter' => $req['filter']
                    ]);
                }

                return $this->render('rent/step_2/index.html.twig', [
                    'rentInfo' => json_encode($req['rentInfo']),
                    'cars' => $req['cars'],
                    'filter' => $req['filter']
                ]);
            }
        }
        return $this->redirectToRoute('rent_index');
    }

    /**
     * Filter the rent_step_2 choices
     * @param Request $request
     * @return Response
     */
    #[Route('/locations/filter', name:'rent_filter', methods: ["POST"])]
    public function filterAvailableRent(Request $request): Response {
        if($request->request) {
            $req = $request->request->all();

            if(!$this->isCsrfTokenValid('token', $req['token'])){
                $this->addFlash("error","Erreur lors de la recherche.");
                return $this->redirectToRoute('rent_index');
            }

            $normalizeCar = array();
            $rentInfo = json_decode($req['rentInfo'], true);

            $filter = array(
                'brand' => isset($req['brand_filter']) ? array_values($req['brand_filter']) : '',
                'model' => isset($req['model_filter']) ? array_values($req['model_filter']) : '',
                'type' => isset($req['type_filter']) ? array_values($req['type_filter']) : '',
                'fuel' => $req['fuel_filter'],
                'gearbox' => $req['gearbox_filter'],
            );

            $cars = $this->repository->filterAvailableCar($rentInfo['pickup_office'], $filter['brand'], $filter['model'], $filter['type'], $filter['fuel'], $filter['gearbox']);

            if(!$cars) {
                $this->addFlash('warning', 'Aucun véhicules trouvés.');
                return $this->redirectToRoute('rent_list', [
                    'redirect' => array(
                        'status' => 'Aucun véhicule trouvée.',
                        'filter' => $filter,
                        'rentInfo' => $rentInfo,
                        'token' => $req['token']
                    )
                ],307);
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
        }

        return $this->redirectToRoute('rent_list', [
            'redirect' => array(
                'rentInfo' => $rentInfo,
                'token' => $req['token'],
                'cars' => $normalizeCar,
                'filter' => $filter
            )
        ],302);
    }

    /**
     * @Route("/rent/test/", name="rent_test", methods={"POST", "GET"})
     * @return Response
     */
    public function rentTest(Request $request): Response {
    
        return $this->render('test.html.twig', [
            // 'cars' => $normalizeCar
        ]);
    }
}