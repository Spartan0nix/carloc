<?php

namespace App\Controller\Admin\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class DepartmentController extends AbstractController
{
    #[Route('/admin/departement/ajouter', name:'admin_department_add')]
    public function new() {
        return $this->render('test.html.twig');
    }
}