<?php

namespace App\Controller\Admin\Controller\Geo;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class AdminCityController extends AbstractController
{
    #[Route('/admin/ville/ajouter', name:'admin_city_add')]
    public function new() {
        return $this->render('test.html.twig');
    }
}
