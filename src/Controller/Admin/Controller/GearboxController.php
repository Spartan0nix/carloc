<?php

namespace App\Controller\Admin\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class GearboxController extends AbstractController
{
    #[Route('/admin/boite-vitesse/ajouter', name:'admin_gearbox_add')]
    public function new() {
        return $this->render('test.html.twig');
    }
}