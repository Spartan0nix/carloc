<?php


namespace App\Controller;

use App\Entity\Car;
use App\Entity\Office;
use App\Entity\Rent;
use App\Entity\Status;
use App\Repository\CarRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class RentController extends AbstractController
{
    /**
     * @var SessionInterface
     */
    private $session;

    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
        
    }

    /**
     * Render the rent_step_4
     * @param Request $request
     * @return Response
     */
    #[Route('/location/recapitulatif', name:'rent_recap', methods: ['POST', 'GET'])]
    public function recapRentReservation(Request $request, CarRepository $repository): Response {
        $rentInfo = $this->session->get('rentInfo');
        if(!$rentInfo){
            return $this->redirectToRoute('office_list');
        }

        if($request->request->all()){
            $rentInfo['carId'] = $request->request->all()['carId'];
            $this->session->set('rentInfo', $rentInfo);
        }

        if(!$this->getUser()){
            $this->session->set('redirect', array(
                'redirect' => true,
                'from' => 'rent_recap'
            ));
            return $this->redirectToRoute('auth_login');
        }

        $this->session->get('redirect') ? $this->session->remove('redirect') : '';
        $car = $repository->findOneBy(['id' => $rentInfo['carId']]);

        $normalizeCar = [
            'id' => $car->getId(),
            'horsepower' => $car->getHorsepower(),
            'daily_price' => $car->getDailyPrice(),
            'release_year' => $car->getReleaseYear(),
            'brand' => $car->getBrandId()->getBrand(),
            'model' => $car->getModelId()->getModel(),
            'gearbox' => $car->getGearboxId()->getGearbox(),
            'fuel' => $car->getFuelId()->getFuel(),
            'color' => $car->getColorId()->getColor()
        ];

        $daily_price = intval($normalizeCar['daily_price']);
        $rent_start = DateTime::createFromFormat('Y-m-d', $rentInfo['start_date']);
        $rent_end = DateTime::createFromFormat('Y-m-d', $rentInfo['end_date']);

        $rent_day_duration = intval(date_diff($rent_start, $rent_end)->format('%a'));
        $reduction = (0.02) * $rent_day_duration;
        $reduction > 0.25 ? $reduction = 0.25 : '';

        $rent_price = ($daily_price * $rent_day_duration) * $reduction;

        return $this->render('rent/step_4/index.html.twig', [
            'car' => $normalizeCar,
            'rentInfo' => $rentInfo,
            'rent_day_duration' => $rent_day_duration,
            'rent_price' => $rent_price,
            'reduction' => $reduction
        ]);
    }

    /**
     * Render the rent_step_5
     * @param Request $request
     * @return Response
     */
    #[Route('/location/confirmation', name:'rent_confirm', methods: ['POST', 'GET'])]
    public function confirmRentReservation(Request $request, EntityManagerInterface $em): Response {
        $req = $request->request->all();
        $user = $this->getUser();
        $verif_car_id = $req['verif_car_id'];
        $rentInfo = $this->session->get('rentInfo');

        if(!$user || $rentInfo['carId'] != $verif_car_id){
            $this->addFlash('error', 'Erreur lors de la résolution de votre requête.');
            return $this->redirectToRoute('car_list');
        }

        $this->session->get('redirect') ? $this->session->remove('redirect') : '';

        $car = $em->getRepository(Car::class)->find($verif_car_id);
        $status = $em->getRepository(Status::class)->find(2);
        $pickup_office = $em->getRepository(Office::class)->find($rentInfo['pickup_office']);

        if($rentInfo['pickup_office'] === $rentInfo['return_office']){
            $return_office = $pickup_office;
        } else {
            $return_office = $em->getRepository(Office::class)->find($rentInfo['return_office']);
        }

        $rent_price = (int) $req['rent_price'];
        $rent_start = DateTime::createFromFormat('Y-m-d', $rentInfo['start_date']);
        $rent_end = DateTime::createFromFormat('Y-m-d', $rentInfo['end_date']);

        $rent = new Rent();
        $rent->setPrice($rent_price);
        $rent->setPickupDate($rent_start);
        $rent->setReturnDate($rent_end);
        $rent->setPickupOfficeId($pickup_office);
        $rent->setReturnOfficeId($return_office);
        $rent->setUserId($user);
        $rent->setCarId($car);
        $rent->setStatusId($status);

        $em->persist($rent);
        $em->flush();
    
        return $this->render('rent/step_5/index.html.twig');
    }
}