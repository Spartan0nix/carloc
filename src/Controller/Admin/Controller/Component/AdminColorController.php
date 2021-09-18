<?php

namespace App\Controller\Admin\Controller\Component;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class AdminColorController extends AbstractController
{
    #[Route('/admin/couleur/ajouter', name:'admin_color_add')]
    public function new() {
        return $this->render('test.html.twig');
    }
}