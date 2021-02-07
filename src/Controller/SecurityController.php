<?php

namespace App\Controller;

use Symfony\Component\Mime\Email;
use App\Entity\Restaurant;
use App\Entity\User;
use App\Form\RegistrationType;
use App\Form\userProfil;
use App\Form\RegistrationTypeRestauType;
use App\Form\restauProfil;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class SecurityController extends AbstractController
{
    /**
     * @Route("/signUpClient", name="signUpClient")
     */
    public function signUpClient(Request $request , UserPasswordEncoderInterface $encoder)
    {
        $user = new User();

        $form = $this->createForm(RegistrationType::class , $user);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {

            $file = $user->getPhoto();
            $file = $form->get('user_photo')->getData();
            
            $fileName = md5(uniqid()).'.'.$file->guessExtension();
            $file->move($this->getParameter('photos_directory'), $fileName); 
            $img = $fileName;
    
            $user->setUserPhoto($img);

            $hash = $encoder->encodePassword($user, $user->getPassword());

            $user->setPasswordUser($hash);

            $user->setType(1);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager -> persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('login');
        }

        return $this->render('signUpClient.html.twig' ,  [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/login", name="login")
    */
    public function login()
    {
        return $this->render('login.html.twig');
    }

    /**
     * @Route("/logout", name="logout")
    */

    public function logout()
    {
        
    }

    /**
     * @Route("/signUpRestau", name="signUpRestau")
    */
    public function signUpRestau(Request $request , UserPasswordEncoderInterface $encoder)
    {
        $restau = new Restaurant();

        $form = $this->createForm(RegistrationTypeRestauType::class , $restau);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {

            $file = $restau->getImage();
            $file = $form->get('restau_image')->getData();
            
            $fileName = md5(uniqid()).'.'.$file->guessExtension();
            $file->move($this->getParameter('photos_directory'), $fileName); 
            $img = $fileName;
    
            $restau->setRestauImage($img);

            $hash = $encoder->encodePassword($restau, $restau->getPassword());

            $restau->setRestauPassword($hash);

            $restau->setType(2);

            $restau->setRestauName($form["restau_name"]->getData());
            $restau->setRestauPhone($form["restau_phone"]->getData());
            $restau->setLocalisation($form["localisation"]->getData());
            $restau->setRestauDescription($form["restau_description"]->getData());
            $restau->setAccepted(false);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager -> persist($restau);
            $entityManager->flush();

            return $this->redirectToRoute('login');
        }
        
        return $this->render('signUpRestau.html.twig' ,  [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/profil/{id}", name="profil")
    */
    public function profil(Request $request ,int $id , UserPasswordEncoderInterface $encoder): Response
    {

        $entityManager = $this->getDoctrine()->getManager();
        $user = $entityManager->getRepository(User::class)->find($id);

        $img= $user->getUserPhoto();

        $user->setPhoto(null);

        $form = $this->createForm(userProfil::class , $user);


        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {

            if (!$user) {
                throw $this->createNotFoundException(
                    'No product found for id '.$id
                );
            }

            if( $form->get('user_photo')->getData() != null)
            {
                $file = $user->getPhoto();
                $file = $form->get('user_photo')->getData();
                
                $fileName = md5(uniqid()).'.'.$file->guessExtension();
                $file->move($this->getParameter('photos_directory'), $fileName); 
                $img = $fileName;
        
                $user->setUserPhoto($img);
            }
            else
            {   
                $user->setPhoto($img);
            }

            $hash = $encoder->encodePassword($user, $form["password_user"]->getData());

            $user->setFullname($form["fullname"]->getData());
            $user->setPasswordUser($hash);
            $user->setUserPhone($form["user_phone"]->getData());
            $entityManager->flush();
        }

        return $this->render('profil.html.twig',[
            'id' => $user->getId() , 'img' => $img ,'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/profilRestau/{id}", name="profilRestau")
    */
    public function profilRestau(Request $request ,int $id, UserPasswordEncoderInterface $encoder): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $restau = $entityManager->getRepository(Restaurant::class)->find($id);

        $img= $restau->getImage();

        $restau->setImage(null);

        $form = $this->createForm(restauProfil::class , $restau);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {

            if (!$restau) {
                throw $this->createNotFoundException(
                    'No product found for id '.$id
                );
            }

            if( $form->get('restau_image')->getData() != null)
            {
                $file = $restau->getImage();
                $file = $form->get('restau_image')->getData();
                
                $fileName = md5(uniqid()).'.'.$file->guessExtension();
                $file->move($this->getParameter('photos_directory'), $fileName); 
                $img = $fileName;
        
                $restau->setRestauImage($img);
            }
            else
            {   
                $restau->setImage($img);
            }

            $hash = $encoder->encodePassword($restau, $form["restau_password"]->getData());

            $restau->setRestauName($form["restau_name"]->getData());
            $restau->setRestauPassword($hash);
            $restau->setRestauPhone($form["restau_phone"]->getData());
            $restau->setLocalisation($form["localisation"]->getData());
            $restau->setRestauDescription($form["restau_description"]->getData());
            $entityManager->flush();
        }


        return $this->render('profilRestau.html.twig',[
            'id' => $restau->getId() ,'form' => $form->createView() , 'img' => $img
        ]);
    }

    /**
     * @Route("/forgetpass", name="forgetpass")
    */

    public function forgetpass(Request $request,\Swift_Mailer $mailer)
    {
        if($request->request->count() > 0)
        {
            $email = $request->request->get('Email');
            dump($email);

            $message = (new \Swift_Message('You Got Mail!'))
               ->setFrom('anwer.baccar2@gmail.com')
               ->setTo($email)
               ->setBody(
                   'http://127.0.0.1:8000/resetpass',
                   'text/plain'
               );

           $mailer->send($message);

        }




    return $this->render('forgetpass.html.twig');

    }

    /**
     * @Route("/resetpass", name="resetpass")
    */

    public function resetpass()
    {


    return $this->render('resetpass.html.twig');

    }
    
    /**
     * @Route("/admin", name="admin")
    */

    public function admin()
    {


    return $this->render('admin.html.twig');

    }

    /**
     * @Route("/admin_client", name="admin_client")
    */

    public function admin_client()
    {

        $repo = $this->getDoctrine()->getRepository(User::class);

        $client = $repo->findAll();

    return $this->render('admin_client.html.twig' , [
        'clients' => $client
    ]);

    }

    /**
     * @Route("/admin_restau", name="admin_restau")
    */

    public function admin_restau(Request $request)
    {

        $repo = $this->getDoctrine()->getRepository(Restaurant::class);

        $restau = $repo->findAll();

        if($request->request->count() > 0)
        {
            
        }

    return $this->render('admin_restau.html.twig' , [
        'restaurants' => $restau
    ]);

    }

    /**
     * @Route("/admin_restau/accept/{id}", name="admin_restau_accept")
    */

    public function accept_restau($id , Request $request)
    {

        $repo = $this->getDoctrine()->getRepository(Restaurant::class);

        $restau = $repo->find($id);

        dump($restau);

        $restau->setAccepted(true);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager -> persist($restau);
        $entityManager->flush();

        // $repo = $this->getDoctrine()->getRepository(Restaurant::class);

        // $restau = $repo->findAll();

        return $this->redirectToRoute('admin_restau');
    }

    /**
     * @Route("/admin_restau/remove/{id}", name="admin_restau_remove")
    */

    public function remove_restau($id , Request $request)
    {

        $repo = $this->getDoctrine()->getRepository(Restaurant::class);

        $restau = $repo->find($id);

        dump($restau);


        $entityManager = $this->getDoctrine()->getManager();
        $entityManager -> remove($restau);
        $entityManager->flush();

        // $repo = $this->getDoctrine()->getRepository(Restaurant::class);

        // $restau = $repo->findAll();

        return $this->redirectToRoute('admin_restau');
    }

}
