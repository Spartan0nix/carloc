<?php

namespace App\Controller\Admin\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class RentController extends AbstractController
{
    #[Route('/admin/location/ajouter', name:'admin_rent_add')]
    public function new() {
        return $this->render('test.html.twig');
    }
}