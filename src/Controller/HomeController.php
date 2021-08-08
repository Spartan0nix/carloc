<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;
use Twig\Cache\CacheInterface as TwigCacheInterface;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="homepage")
     * @return Response
     */
    public function index(CacheInterface $cache): Response{
        // return $cache->get('homepage', function() {
        //     return $this->render("homepage.html.twig");
        // });

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