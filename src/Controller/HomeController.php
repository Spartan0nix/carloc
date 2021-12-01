<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class HomeController extends AbstractController
{
    #[Route('/', name:'homepage')]
    public function index(): Response{
        return $this->render("homepage.html.twig");
    }

    /**
     * @Route("/test", name="test")
     * @return Response
     */
    public function test(): Response {
        return $this->render('test.html.twig');
    }
}