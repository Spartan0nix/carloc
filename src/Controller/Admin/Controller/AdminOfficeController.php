<?php

namespace App\Controller\Admin\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class AdminOfficeController extends AbstractController
{
    #[Route('/admin/agence/ajouter', name:'admin_office_add')]
    public function new() {
        return $this->render('test.html.twig');
    }
}