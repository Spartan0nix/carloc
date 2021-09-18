<?php

namespace App\Controller\Admin\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class AdminUserController extends AbstractController
{
    #[Route('/admin/utilisateur/ajouter', name:'admin_user_add')]
    public function new() {
        return $this->render('test.html.twig');
    }
}