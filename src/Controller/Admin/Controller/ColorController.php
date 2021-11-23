<?php

namespace App\Controller\Admin\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ColorController extends AbstractController
{
    #[Route('/admin/couleur/ajouter', name:'admin_color_add')]
    public function new() {
        return $this->render('test.html.twig');
    }
}