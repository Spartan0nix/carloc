<?php

namespace App\Controller\Admin\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class TypeController extends AbstractController
{
    #[Route('/admin/type/ajouter', name:'admin_type_add')]
    public function new() {
        return $this->render('test.html.twig');
    }
}