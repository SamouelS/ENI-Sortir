<?php

namespace App\Controller;

use App\Entity\Sortie;
use App\Form\SortieType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class SortieController extends AbstractController
{
    /**
     * @Route("/sortie/{id}", name="sortie_detail", requirements={"id": "\d+"})
     */
    public function datail($id): Response
    {
        $sortieRepo = $this->getDoctrine()->getRepository(Sortie::class);
        $sortie = $sortieRepo->find($id);
        return $this->render('sortie/detail.html.twig', [
            'controller_name' => 'SortieController',
            'title' => 'le nom de la sortie',
            'sortie' => $sortie
        ]);
    }
    /**
     * @Route("/sortie/add", name="sortie_add")
     */
    public function add(EntityManagerInterface $em, Request $request): Response
    {
        $sortie = new Sortie();
        $form = $this->createForm(SortieType::class, $sortie);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {            
            $em->persist($sortie);
            $em->flush();
            $this->addFlash('success', 'la sortie à bien été insérée ! ');
            return $this->redirectToRoute("home");
        }
        return $this->render('sortie/add.html.twig', [
            "form" => $form->createView()
        ]);
    }
}
