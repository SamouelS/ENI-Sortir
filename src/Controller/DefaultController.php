<?php

namespace App\Controller;

use DateInterval;
use App\Entity\Etat;
use App\Entity\Sortie;
use App\Entity\SortieFilter;
use App\Form\SortieFilterType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

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
        $sorties = $this->checkEtat($sorties);
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
    public function checkEtat($sorties){
        $em = $this->getDoctrine()->getManager();

        $dateNow = new \DateTime();
        $etatRepo = $this->getDoctrine()->getRepository(Etat::class);
        $etatArchive = $etatRepo->find(7);
        $etatCloture = $etatRepo->find(6);
        $etatOuverte = $etatRepo->find(2);
        $etatEnCours = $etatRepo->find(3);
        $etatPassee = $etatRepo->find(4);
        foreach ($sorties as $sortie) {
            $modif = false;
            $heure = $sortie->getDuree()->format('H');
            $minute = $sortie->getDuree()->format('s');
            $dateClone = clone $sortie->getDateHeureDebut();
            // dump($sortie->getDateHeureDebut());
            // dump($dateClone->add(new DateInterval('PT'.$heure.'H'.$minute.'M')));
            // dump($sortie->getDateHeureDebut());
            // die;
            if($sortie->getEtat()->getId() != 5){
                if($dateNow > $sortie->getDateHeureDebut() && $dateNow < $dateClone->add(new DateInterval('PT'.$heure.'H'.$minute.'M'))){
                    $sortie->setEtat($etatEnCours);
                    $modif = true;
                }
                if($dateNow > $dateClone->add(new DateInterval('PT'.$heure.'H'.$minute.'M')) && $sortie->getEtat()->getId() != 7){
                    $sortie->setEtat($etatPassee);
                    $modif = true;   
                }
                if($sortie->getDateLimiteInscription()>$dateNow && $sortie->getEtat()->getId() != 2 && $sortie->getEtat()->getId() == 6 && $sortie->getEtat()->getId() != 7){
                    $sortie->setEtat($etatOuverte);
                    $modif = true;
                }
                if($sortie->getDateLimiteInscription()<$dateNow && $sortie->getEtat()->getId() != 6 && $sortie->getEtat()->getId() != 7 && $sortie->getEtat()->getId() != 3  && $sortie->getEtat()->getId() != 4){
                    $sortie->setEtat($etatCloture);
                    $modif = true;
                }
    
                if($dateNow->diff($sortie->getDateHeureDebut())->m >=1 && $sortie->getEtat()->getId() != 7){              
                    $sortie->setEtat($etatArchive);
                    $modif = true;  
                }
                
                if ($modif) {
                    $em->persist($sortie);
                    $em->flush();
                }
            }
        }
        return $sorties;
    }
}
