<?php

namespace App\Controller\Admin\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ModelController extends AbstractController
{
    #[Route('/admin/model/ajouter', name:'admin_model_add')]
    public function new() {
        return $this->render('test.html.twig');
    }
}