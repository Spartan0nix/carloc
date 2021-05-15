<?php


namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class RentController extends AbstractController
{
    
    #[Route('/location', name: 'rent_index')]
    public function index(){

        return $this->render('rent/step_1/index.html.twig');
    }
}