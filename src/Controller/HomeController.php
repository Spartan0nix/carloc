<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Cache\ItemInterface;


class HomeController extends AbstractController
{
    /**
     * @Route("/", name="homepage")
     * @return Response
     */
    public function index(): Response{
        return $this->render("homepage.html.twig");
    }

    /**
     * @Route("/test", name="test")
     * @return Response
     */
    public function test(): Response {

        $cache = new FilesystemAdapter();

        $value = $cache->get('my_cache_key', function(ItemInterface $item) {
            $item->expiresAfter(3600);

            $computedValue = 'test';

            return $computedValue;
        });

        return $this->render('test.html.twig');
    }
}