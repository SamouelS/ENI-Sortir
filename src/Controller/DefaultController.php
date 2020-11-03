<?php

namespace App\Controller;

use App\Entity\Sortie;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function home(): Response
    {
        $sortieRepo = $this->getDoctrine()->getRepository(Sortie::class);
        $sorties = $sortieRepo->findAllOrderByCampus();
        
        return $this->render('default/home.html.twig', [
            'controller_name' => 'DefaultController',
            'sorties'=>$sorties,
            'user'=>$this->getUser()
        ]);
        
    }
}
