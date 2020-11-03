<?php

namespace App\Controller;

use DateInterval;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TestController extends AbstractController
{
    /**
     * @Route("/test", name="test")
     */
    public function index(): Response
    {
        $date = new DateTime();
        dump($date->add(new DateInterval("P1D")));
        die;
        return $this->render('test/index.html.twig', [
            'controller_name' => 'TestController',
        ]);
    }
}
