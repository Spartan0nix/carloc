<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class OfficeController extends AbstractController
{
    /**
     * Render the rent_step_1 page
     * @return void
     */
    #[Route('/agence/list', name: 'office_list')]
    public function index(){
        return $this->render('rent/step_1/index.html.twig');
    }
}