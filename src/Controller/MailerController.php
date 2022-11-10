<?php

namespace App\Controller;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;

class MailerController extends AbstractController
{
    /**
     * @Route("/mail", name="app_mailer")
     */
    public function sendEmail(MailerInterface $mailer)
    {

        $table = 'table1';
        $text = 'FOR THE HORDE';
        $email = (new TemplatedEmail())
            ->from('table1@menukarte.wip')
            ->to('kelner@menukarte.wip')
            ->subject('something')

            ->htmlTemplate('mailer/mail.html.twig')
            ->context([
                'table' =>  $table,
                'text' => $text
            ]);

        $mailer->send($email);


        return new Response('Email send.');

//        return $this->render('mailer/index.html.twig', [
//            'controller_name' => 'MailerController',
//        ]);

    }
}
