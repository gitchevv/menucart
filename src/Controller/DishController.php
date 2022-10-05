<?php

namespace App\Controller;

use App\Entity\Dish;
use App\Form\DishType;
use App\Repository\DishRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Config\Framework\HttpClientConfig;
use Symfony\Config\Framework\Messenger\TransportConfig\RetryStrategyConfig;


/**
 * @Route("/dish", name="dish.")
 */
class DishController extends AbstractController
{
    /**
     * @Route("/edit", name="edit")
     */
    public function index(DishRepository $dr)
    {

        $dish = $dr->findAll();

        return $this->render('dish/index.html.twig', [
            'dishes' => $dish
        ]);

//        return new Response("<h1>Added pizza number: </h1>");

    }

    /**
     * @Route("/create", name="create")
     */
    public function create(ManagerRegistry $doctrine, Request $request)
    {

        $dish = new Dish();
        $form = $this->createForm(DishType::class, $dish);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            //Entity Manager
            $em = $doctrine->getManager();


            $image = $request->files->get('dish')['attachment'];

            if($image) {
                $filename = md5(uniqid()) . "." . $image->guessClientExtension();

                $image->move(
                    $this->getParameter('images_folder'),
                    $filename
                );
                $dish->setImage($filename);

            }


            $em->persist($dish);
            $em->flush();

            return $this->redirect($this->generateUrl('dish.edit'));
        }



        //Response
        return $this->render('dish/create.html.twig', [
            'createForm' => $form->createView()
        ]);
    }

    /**
     * @Route("/delete/{id}", name="delete")
     */
    public function delete($id, DishRepository $dr, ManagerRegistry $doctrine)
    {
        $em = $doctrine->getManager();
        $dish = $dr->find($id);

        $em->remove($dish);
        $em->flush();

        //message
        $this->addFlash('success', $dish->getName() . ' removed from the menu.');

        return $this->redirect($this->generateUrl('dish.edit'));
    }

    /**
     * @Route("/show/{id}", name="show")
     */
    public function show($id,DishRepository $dish)
    {
        $dr = $dish->find($id);
        return $this->render('dish/show.html.twig', [
            'dish' => $dr
        ]);
    }
}
