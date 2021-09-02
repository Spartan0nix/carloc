<?php

namespace App\Controller;

use App\Entity\Address\City;
use App\Entity\Address\Department;
use App\Security\LoginFormAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;

class UserController extends AbstractController
{
    #[Route('/account/change-identity', name:'user_change_identity')]
    public function changeIdentity(Request $request, EntityManagerInterface $em, GuardAuthenticatorHandler $guardAuthenticatorHandler, LoginFormAuthenticator $loginFormAuthenticator){
        $user = $this->getUser();
        if(!$user){
            $this->addFlash('error', 'Accès restreint, authentification requise.');
            return $this->redirectToRoute('auth_login');
        }

        $req = $request->request->all();
        $last_name = $req['last_name'];
        $first_name = $req['first_name'];
        $email = $req['email'];
        $old_email = $user->getEmail();
        
        $user->setLastName($last_name);
        $user->setFirstName($first_name);
        $user->setEmail($email);
        $em->flush();

        if($email != $old_email){
            if($guardAuthenticatorHandler->authenticateUserAndHandleSuccess(
                $this->getUser(), 
                $request, 
                $loginFormAuthenticator, 
                'main'
            )){
                $this->addFlash('success', 'Informations modifiées avec succès.');
                return $this->redirectToRoute('auth_account');

            } else {
                $this->redirectToRoute('auth_login');
            }
        }

        $this->addFlash('success', 'Informations modifiées avec succès.');
        return $this->redirectToRoute('auth_account');
    }

    #[Route('/account/change-address', name:'user_change_address')]
    public function changeAddress(Request $request, EntityManagerInterface $em): Response {
        $user = $this->getUser();
        if(!$user){
            $this->addFlash('error', 'Accès restreint, authentification requise.');
            return $this->redirectToRoute('auth_login');
        }

        $req = $request->request->all();
        $address = $req['address'];
        $city_id = $req['city_id'];
        $department_id = $req['department_id'];
        
        $user->setAddress($address);
        $city = $this->getDoctrine()->getRepository(City::class)->findOneBy(['id' => $city_id]);
        $user->setCityId($city);
        $department = $this->getDoctrine()->getRepository(Department::class)->findOneBy(['id' => $department_id]);
        $user->setDepartmentId($department);
        $em->flush();

        $this->addFlash('success', 'Informations modifiées avec succès.');
        return $this->redirectToRoute('auth_account');
    }

    #[Route('/compte/mes-locations', name:'user_rent_list')]
    public function listUserRent(): Response {
        
        $user = $this->getUser();
        if(!$user){
            $this->addFlash('error', 'Accès restreint, authentification requise.');
            return $this->redirectToRoute('auth_login');
        }

        $rents = $user->getRents();
        $normalize_rents = [];

        foreach($rents as $rent){
            array_push($normalize_rents, [
                'id' => $rent->getId(),
                'price' => $rent->getPrice(),
                'pickup_date' => $rent->getPickupDate(),
                'return_date' => $rent->getReturnDate(),
                'car' => [
                    'id' => $rent->getCarId()->getId(),
                    'brand' => $rent->getCarId()->getBrandId()->getBrand(),
                    'model' => $rent->getCarId()->getModelId()->getModel(),
                    'daily_price' => $rent->getCarId()->getDailyPrice(),
                ],
                'status' => [
                    'id' => $rent->getStatusId()->getId(),
                    'status' => $rent->getStatusId()->getStatus(),
                ]
            ]);
        }

        return $this->render('security/rent_list.html.twig', [
            'rents' => $normalize_rents
        ]);
    }
}