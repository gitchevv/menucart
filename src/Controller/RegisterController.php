<?php

namespace App\Controller;

use App\Entity\User;

use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class RegisterController extends AbstractController
{
    /**
     * @Route("/register", name="app_register")
     */
    public function register(Request $request, UserPasswordHasherInterface $passwordHasher, ManagerRegistry $doctrine, ValidatorInterface $validator)
    { 

        $regForm = $this->createFormBuilder()
            ->add('username', TextType::class, ['label' => 'Employee'])
            ->add('password', RepeatedType::class,
                [
                    'type' => PasswordType::class,
                    'required' => true,
                    'first_options' => ['label' => 'Password'],
                    'second_options' => ['label' => 'Confirm Password']
                ])
            ->add('register', SubmitType::class)
            ->getForm();

        $regForm->handleRequest($request);

        if($regForm->isSubmitted()) {

            $input = $regForm->getData();

            $user = new User();
            $user->setUsername($input['username']);
            $user->setPassword( $passwordHasher->hashPassword($user, $input['password']) );

            $user->setRawPassword();
            $errors = $validator->validate($user);

            if( count($errors) > 0 ) {
                
                return $this->render('register/index.html.twig', [
                    'regform' => $regForm->createView(),
                    'errors' => $errors
                ]);
        
            } else {
                $em = $doctrine->getManager();
                $em->persist($user);
                $em->flush();
            };

            return $this->redirect($this->generateUrl('app_home'));
        }

        return $this->render('register/index.html.twig', [
                    'regform' => $regForm->createView(),
                    'errors' => null
        ]);
    }
}
