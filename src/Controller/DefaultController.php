<?php

namespace App\Controller;

use App\Entity\Sortie;
use App\Entity\SortieFilter;
use App\Form\SortieFilterType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function home(Request $request): Response
    {
        $sortieFilter = new SortieFilter();
        $form = $this->createForm(SortieFilterType::class, $sortieFilter);

        $sortieRepo = $this->getDoctrine()->getRepository(Sortie::class);
        $sorties = $sortieRepo->findAllOrderByCampus();
        
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {   
            $sorties = $sortieRepo->findByFilter($sortieFilter);

            return $this->render('default/home.html.twig', [
                'controller_name' => 'DefaultController',
                'sorties'=>$sorties,
                'user'=>$this->getUser(),
                'form'=> $form->createView(),
            ]);
        }
        return $this->render('default/home.html.twig', [
            'controller_name' => 'DefaultController',
            'sorties'=>$sorties,
            'user'=>$this->getUser(),
            'form'=> $form->createView(),
        ]);
        


    }
}
