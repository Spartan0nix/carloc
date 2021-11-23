<?php

namespace App\Controller\Admin\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class FuelController extends AbstractController
{
    #[Route('/admin/energie/ajouter', name:'admin_fuel_add')]
    public function new() {
        return $this->render('test.html.twig');
    }
}