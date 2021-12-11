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
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class RentController extends AbstractController
{

    public function calculDuration(string $start_date, string $end_date): string {
        $rent_start = DateTime::createFromFormat('Y-m-d', $start_date);
        $rent_end = DateTime::createFromFormat('Y-m-d', $end_date);

        return intval(date_diff($rent_start, $rent_end)->format('%a'));
    }

    public function calculReduction(string $rent_day_duration): float {
        $reduction = (0.02) * $rent_day_duration;
        $reduction > 0.25 ? $reduction = 0.25 : '';

        return $reduction;
    }

    public function calculTotalPrice(string $daily_price, string $rent_day_duration, string $reduction) {
        return ($daily_price * $rent_day_duration) - (($daily_price * $rent_day_duration)* $reduction);
    }
    
    /**
     * Render the rent_step_4
     * @param Request $request
     * @return Response
     */
    #[Route('/location/recapitulatif', name:'rent_recap', methods: ['POST', 'GET'])]
    public function summary(Request $request, CarRepository $repository, Session $session): Response {
        $rentInfo = $session->get('rentInfo');
        if(!$rentInfo){
            return $this->redirectToRoute('office_list');
        }

        $req = $request->request->all();
        if(!isset($req['carId'])){
            $req = $request->query->all();
            if(!isset($req['carId'])) {
                $session->getFlashBag()->add('error', 'Erreur lors de la vérification de votre requête.');
                return $this->redirectToRoute('car_list');
            }
        }

        if(!$this->getUser()){
            $session->set('redirect', array(
                'redirect' => true,
                'from' => 'rent_recap',
                'param' => [
                    'carId' => $req['carId']
                ]
            ));
            return $this->redirectToRoute('auth_login');
        }
        $session->get('redirect') ? $session->remove('redirect') : '';

        $car = $repository->findOneBy(['id' => $req['carId']]);
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
        $rent_day_duration = $this->calculDuration($rentInfo['start_date'], $rentInfo['end_date']);
        $reduction = $this->calculReduction($rent_day_duration);

        $rent_price = $this->calculTotalPrice($daily_price, $rent_day_duration, $reduction);

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
    #[Route('/location/confirmation', name:'rent_confirm', methods: ['POST'])]
    public function confirm(Request $request, EntityManagerInterface $em, Session $session): Response {
        $rentInfo = $session->get('rentInfo');
        if(!$rentInfo) {
            return $this->redirectToRoute('office_list');
        }

        $req = $request->request->all();
        if(!$req['car_id']) {
            $session->getFlashBag()->add('error', 'Erreur lors de la vérification de votre requête.');
            return $this->redirectToRoute('office_list');
        }
        $car_id = $req['car_id'];

        $user = $this->getUser();
        if(!$user){
            $session->getFlashBag()->add('error', 'Vous devez vous authentifier pour effectuer cette action.');
            return $this->redirectToRoute('auth_login');
        }

        $existing_rent = $em->getRepository(Rent::class)->findOneBy(['car_id' => $car_id]);
        if($existing_rent){
            $session->getFlashBag()->add('warning', 'Ce véhicule est déjà associé à une location.');
            return $this->redirectToRoute('car_list');
        }

        $car = $em->getRepository(Car::class)->find($car_id);
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