<?php

namespace App\Controller;

use App\Entity\Sortie;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class SortieController extends AbstractController
{
    /**
     * @Route("/sortie", name="sortie")
     */
    public function sortie(): Response
    {
        $sortieRepo = $this->getDoctrine()->getRepository(Sortie::class);
        $sorties = $sortieRepo->findAllOrderByCampus();
        dump($sorties);
        die;
        return $this->render('sortie/index.html.twig', [
            'controller_name' => 'SortieController',
            'title' => 'le nom de la sortie',
        ]);
    }
}
