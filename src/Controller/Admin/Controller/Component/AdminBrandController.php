<?php

namespace App\Controller\Admin\Controller\Component;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class AdminBrandController extends AbstractController
{
    #[Route('/admin/marque/ajouter', name:'admin_brand_add')]
    public function new() {
        return $this->render('admin/car/brand/new.html.twig');
    }
}