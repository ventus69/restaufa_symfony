<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Restaurant;
use App\Form\CommentformType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(): Response
    {
        $user = $this->getUser();
        
        if($user)
        {
        if($user->getType() == 0)
        {
            return $this->render('admin.html.twig');
        }
        }

        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }

    /**
     * @Route("/viewRestau", name="viewRestau")
    */
    public function viewRestau()
    {
        $repo = $this->getDoctrine()->getRepository(Restaurant::class);

        $restau = $repo->findAll();

        return $this->render('viewRestau.html.twig' , [
            'restaurants' => $restau
        ]);
    }

    /** 
     * @Route("/viewRestau/restauInfo/{id}", name="restauInfo")
    */
    public function restauInfo($id , Request $request)
    {
        $repo = $this->getDoctrine()->getRepository(Restaurant::class);

        $restau = $repo->find($id);

        $comment = new Comment();

        $form = $this->createForm(CommentformType::class , $comment);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {

            $user = $this->getUser();

            $comment->setUsername($user->getFullname());
            $comment->setRestaurant($restau);
            $comment->setCreatedAt(new \DateTime('now'));
            
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager -> persist($comment);
            $entityManager->flush();

        }


        return $this->render('restauInfo.html.twig' , [
            'restau' => $restau , 'form' => $form->createView()
        ]);
    }

        /**
     * @Route("/about", name="about")
    */
    public function about()
    {
        return $this->render('about.html.twig');
    }


}
