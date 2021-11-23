<?php

namespace App\Controller\Admin\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class CarController extends AbstractController
{
    #[Route('/admin/voiture/ajouter', name:'admin_car_add')]
    public function new() {
        return $this->render('test.html.twig');
    }
}