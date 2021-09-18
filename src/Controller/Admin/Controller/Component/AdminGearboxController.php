<?php

namespace App\Controller\Admin\Controller\Component;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class AdminGearboxController extends AbstractController
{
    #[Route('/admin/boite-vitesse/ajouter', name:'admin_gearbox_add')]
    public function new() {
        return $this->render('test.html.twig');
    }
}