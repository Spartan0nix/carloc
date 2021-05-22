<?php


namespace App\Controller\Rent;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class RentController extends AbstractController
{
    #[Route('/location', name: 'rent_index')]
    public function index(){
        return $this->render('rent/step_1/index.html.twig');
    }
}