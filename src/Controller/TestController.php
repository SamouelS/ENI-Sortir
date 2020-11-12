<?php

namespace App\Controller;

use DateTime;
use DateInterval;
use App\Entity\Sortie;
use PhpParser\Node\Expr\New_;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TestController extends AbstractController
{
    /**
     * @Route("/test", name="test")
     */
    public function index(): Response
    {


        $sortieRepo = $this->getDoctrine()->getRepository(Sortie::class);
        $sorties = $sortieRepo->findAllOrderByCampus();
        $dateNow = new \DateTime();
        foreach ($sorties as $sortie) {
            if($sortie->getDateHeureDebut()< $dateNow->add(new DateInterval('P1M')) && $sortie->getEtat()->getId() != 7){
                dump('ok');
            }
        }
        dump($sorties);
        die;
        return $this->render('test/index.html.twig', [
            'controller_name' => 'TestController',
        ]);
    }
}
