<?php

namespace App\Controller;

use App\Repository\DishRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="app_home")
     */
    public function index(DishRepository $gr): Response
    {

        $dishes = $gr->findAll();
        $random_dishes = array_rand($dishes, 2);


        return $this->render('home/index.html.twig', [
            'dish1' => $dishes[$random_dishes[0]],
            'dish2' => $dishes[$random_dishes[1]],
        ]);
    }

    /**
     * @Route("/start", name="start")
     */
    public function start(): Response
    {
        return $this->render('home/start.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }

    public function end(): Response
    {
        return $this->render('home/start.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }

}
