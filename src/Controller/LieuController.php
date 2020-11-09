<?php

namespace App\Controller;

use App\Entity\Lieu;
use App\Form\LieuType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LieuController extends AbstractController
{
    /**
     * @Route("/lieu/add", name="lieu_add")
     */
    public function add(EntityManagerInterface $em, Request $request): Response
    {
        $lieu = new Lieu();
        $form = $this->createForm(LieuType::class, $lieu);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($lieu);
            $em->flush();
            $this->addFlash('success', 'Ce lieu a bien été ajouté ');
            return $this->redirectToRoute("sortie_add");
        }
        return $this->render('lieu/index.html.twig', [
            "form" => $form->createView()
        ]);
    }
}
