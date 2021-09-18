<?php

namespace App\Controller\Admin\Controller\Component;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class AdminTypeController extends AbstractController
{
    #[Route('/admin/type/ajouter', name:'admin_type_add')]
    public function new() {
        return $this->render('test.html.twig');
    }
}