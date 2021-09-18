<?php

namespace App\Controller\Admin\Controller\Component;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class AdminModelController extends AbstractController
{
    #[Route('/admin/model/ajouter', name:'admin_model_add')]
    public function new() {
        return $this->render('test.html.twig');
    }
}