<?php

namespace App\Controller\Admin\Controller\Component;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class AdminFuelController extends AbstractController
{
    #[Route('/admin/energie/ajouter', name:'admin_fuel_add')]
    public function new() {
        return $this->render('test.html.twig');
    }
}