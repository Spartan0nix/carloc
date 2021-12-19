<?php

namespace App\Controller;

use App\Form\UserReservationType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;

class OfficeController extends AbstractController
{
    /**
     * Render the rent_step_1 page
     * @return void
     */
    #[Route('/agence/list', name: 'office_list')]
    public function index(Request $request, Session $session){
        $form = $this->createForm(UserReservationType::class);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $data = $form->getData();
            $pickup_office = $data['pickup_office']->getId();
            $return_office = $data['return_office'];
            $return_office = $return_office === null ? $pickup_office : $data['return_office']->getId();
            
            $session->set('rent_info', [
                'pickup_office' => $pickup_office,
                'return_office' => $return_office,
                'pickup_date' => $data['pickup_date'],
                'return_date' => $data['return_date']
            ]);

            return $this->redirectToRoute('car_list');
        }

        return $this->render('rent/step_1/index.html.twig', [
            'form' => $form->createView()
        ]);
    }
}