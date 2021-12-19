<?php


namespace App\Controller;

use App\Entity\Car;
use App\Entity\Office;
use App\Entity\Rent;
use App\Entity\Status;
use App\Normalizer\CarNormalizer;
use App\Repository\CarRepository;
use DateTimeInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class RentController extends AbstractController
{
    public function __construct(private SessionInterface $session){}

    public function calculDuration(DateTimeInterface $start_date, DateTimeInterface $end_date): string {
        return intval(date_diff($start_date, $end_date)->format('%a'));
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
    #[Route('/location/{id}/recapitulatif', name:'rent_recap', methods: ['GET'])]
    public function summary(String $id, CarRepository $repository, CarNormalizer $normalizer): Response {
        if(!$this->getUser()){
            $this->session->set('redirect', array(
                'redirect' => true,
                'from' => 'rent_recap',
                'param' => [
                    'id' => $id
                ]
            ));
            return $this->redirectToRoute('auth_login');
        }

        $rent_info = $this->session->get('rent_info');
        if(!$rent_info){
            return $this->redirectToRoute('office_list');
        }

        $car = $repository->findOneBy(['id' => $id]);
        $normalize_car = $normalizer->normalize($car);

        $daily_price = intval($normalize_car['daily_price']);
        $rent_day_duration = $this->calculDuration($rent_info['pickup_date'], $rent_info['return_date']);
        $reduction = $this->calculReduction($rent_day_duration);

        $rent_price = $this->calculTotalPrice($daily_price, $rent_day_duration, $reduction);

        return $this->render('rent/step_4/index.html.twig', [
            'car' => $normalize_car,
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
    public function confirm(Request $request, EntityManagerInterface $em): Response {
        $rent_info = $this->session->get('rent_info');
        if(!$rent_info) {
            return $this->redirectToRoute('office_list');
        }

        $req = $request->request->all();
        if(!$req['car_id']) {
            $this->addFlash('error', 'Erreur lors de la validation de votre requête.');
            return $this->redirectToRoute('car_list');
        }

        $user = $this->getUser();
        if(!$user){
            $this->addFlash('error', 'Vous devez vous authentifier pour effectuer cette action.');
            return $this->redirectToRoute('auth_login');
        }

        $existing_rent = $em->getRepository(Rent::class)->findOneBy(['car_id' => $req['car_id']]);
        if($existing_rent){
            $this->addFlash('warning', 'Ce véhicule est déjà associé à une location.');
            return $this->redirectToRoute('car_list');
        }

        $car = $em->getRepository(Car::class)->find($req['car_id']);
        $status = $em->getRepository(Status::class)->find(2);
        $pickup_office = $em->getRepository(Office::class)->find($rent_info['pickup_office']);
        
        if($rent_info['pickup_office'] === $rent_info['return_office']){
            $return_office = $pickup_office;
        } else {
            $return_office = $em->getRepository(Office::class)->find($rent_info['return_office']);
        }

        $rent_price = (int) $req['rent_price'];

        $rent = new Rent();
        $rent->setPrice($rent_price);
        $rent->setPickupDate($rent_info['pickup_date']);
        $rent->setReturnDate($rent_info['return_date']);
        $rent->setPickupOfficeId($pickup_office);
        $rent->setReturnOfficeId($return_office);
        $rent->setUserId($user);
        $rent->setCarId($car);
        $rent->setStatusId($status);

        $em->persist($rent);
        $em->flush();
        $this->session->remove('rent_info');
    
        return $this->render('rent/step_5/index.html.twig');
    }
}