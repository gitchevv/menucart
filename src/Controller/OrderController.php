<?php

namespace App\Controller;

use App\Entity\Dish;
use App\Entity\Order;
use App\Repository\DishRepository;
use App\Repository\OrderRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OrderController extends AbstractController
{
    /**
     * @Route("/order", name="app_orders")
     */
    public function index(OrderRepository $orderRepository): Response
    {

        $orders = $orderRepository->findBy(
            ['dtable' => 'table1']
        );

        return $this->render('order/index.html.twig', [
            'orders' => $orders,
        ]);
    }
    /**
     * @Route("/order/{id}", name="app_order")
     */
    public function order($id, DishRepository $dr, ManagerRegistry $doctrine)
    {
        $dish = $dr->find($id);


        $order = new Order();
        $order->setDtable('table1');
        $order->setName($dish->getName());
        $order->setOdernr($dish->getId());
        $order->setPrice($dish->getPrice());
        $order->setStatus('open');

//        dump($dish);

        $em = $doctrine->getManager();
        $em->persist($order);
        $em->flush();

        $this->addFlash('order', $order->getName() . " was ordered.");

        return $this->redirect($this->generateUrl('app_menu'));

    }

    /**
     * @Route("/status/{id},{status}", name="status")
     */
    public function status($id, ManagerRegistry $doctrine, $status){

        $em = $doctrine->getManager();
        $order = $em->getRepository(Order::class)->find($id);

        $order->setStatus($status);
        $em->flush();

        return $this->redirect($this->generateUrl('app_orders'));

    }

    /**
     * @Route("/delete/{id}", name="delete")
     */
    public function delete($id, OrderRepository $or, ManagerRegistry $doctrine){

        $em = $doctrine->getManager();
        $order = $or->find($id);
        $em->remove($order);
        $em->flush();

        return $this->redirect($this->generateUrl('app_orders'));
    }
}
