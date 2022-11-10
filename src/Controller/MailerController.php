<?php

namespace App\Controller;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;

class MailerController extends AbstractController
{
    /**
     * @Route("/mail", name="app_mailer")
     */


    public function sendEmail(MailerInterface $mailer, Request $request)
    {

        $emailForm = $this->createFormBuilder()
            ->add('message', TextareaType::class, ['attr' => array('rows' => 5)])
            ->add('submit', SubmitType::class)
            ->getForm();

        $emailForm->handleRequest($request);

        if ($emailForm->isSubmitted()) {

            $input = $emailForm->getData();
            $text = $input['message'];

            $table = 'table1';
            $text = 'FOR THE HORDE';
            $email = (new TemplatedEmail())
                ->from('table1@menukarte.wip')
                ->to('kelner@menukarte.wip')
                ->subject('Message')
                ->htmlTemplate('mailer/mail.html.twig')
                ->context([
                    'table' => $table,
                    'text' => $text
                ]);

            $mailer->send($email);

            $this->addFlash('Message', 'Message was send.');

            return $this->redirect($this->generateUrl('app_mailer'));
        }

        return $this->render('mailer/index.html.twig',
            [
                'emailForm' => $emailForm->createView()
            ]);

    }
}
