<?php


namespace App\Controller\Rent;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class RentController extends AbstractController
{
    #[Route('/location', name: 'rent_index')]
    public function index(){

        $this->addFlash("success","La tâche n'a pas pu être ajoutée");

        return $this->render('rent/step_1/index.html.twig');
    }


    #[Route('/locations', name: 'rent_list', methods: ["POST"])]
    public function listAvailableRent(Request $request) {

        // if($request->request){
        //     $req = $request->request->all();
        //     if(!$this->isCsrfTokenValid('token', $req['token'])){
        //         return $this->redirectToRoute('rent_index');
        //     }
        //     dump($req);
        // }
        
        // return $this->render('rent/step_2/index.html.twig')
        // $this->addFlash("error","La tâche n'a pas pu être ajoutée");
    }
}