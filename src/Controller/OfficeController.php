<?php

namespace App\Controller;

use App\Form\UserReservationType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class OfficeController extends AbstractController
{
    /**
     * Render the rent_step_1 page
     * @return void
     */
    #[Route('/agence/list', name: 'office_list')]
    public function index(Request $request){
        $form = $this->createForm(UserReservationType::class);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            return $this->redirectToRoute('car_list', $form->getData(), 307);
        }

        return $this->render('rent/step_1/index.html.twig', [
            'form' => $form->createView()
        ]);
    }
}