<?php

namespace App\Controller;

use App\Entity\Etat;
use App\Entity\Participant;
use App\Entity\Sortie;
use App\Form\SortieType;
use DateTime;
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
            $etatRepo = $this->getDoctrine()->getRepository(Etat::class);  
            $sortie->setEtat($etatRepo->find(1));
            $sortie->setOrganisateur($this->getUser());
            $em->persist($sortie);
            $em->flush();
            $this->addFlash('success', 'La sortie a bien été insérée ! ');
            return $this->redirectToRoute("home");
        }
        return $this->render('sortie/add.html.twig', [
            "form" => $form->createView()
        ]);
    }
    /**
     * @Route("/sortie/edit/{id}", name="sortie_edit")
     */
    public function edit($id,EntityManagerInterface $em, Request $request): Response
    {
        $sortieRepo = $this->getDoctrine()->getRepository(Sortie::class);
        $sortie = $sortieRepo->find($id);
        $form = $this->createForm(SortieType::class, $sortie);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {          
            $em->persist($sortie);
            $em->flush();
            $this->addFlash('success', 'La sortie a bien été modifiée ! ');
            return $this->redirectToRoute("home");
        }
        return $this->render('sortie/edit.html.twig', [
            "form" => $form->createView(),
            "sortie" => $sortie,
        ]);
    }
    /**
     * @Route("/sortie/register/{idSortie}/{idParticipant}", name="sortie_register", requirements={"idSortie": "\d+","idParticipant": "\d+"})
     */
    public function register($idSortie, $idParticipant, EntityManagerInterface $em): Response
    {
        $particiapantRepo = $this->getDoctrine()->getRepository(Participant::class);
        $participant = $particiapantRepo->find($idParticipant);
        $sortieRepo = $this->getDoctrine()->getRepository(Sortie::class);
        $sortie = $sortieRepo->find($idSortie);

        if($sortie->getDateLimiteInscription() < new DateTime()) {
            $this->addFlash('danger', 'Les inscriptions sont clôturées pour la sortie'.$sortie->getNom().'"');
            return $this->redirectToRoute("home");
        }

        if(count($sortie->getParticipants()) >= $sortie->getNbInscriptionsMax()) {
            $this->addFlash('danger', 'Le nombre maximum de participants a déjà été atteint pour la sortie "'.$sortie->getNom().'"');
            return $this->redirectToRoute("home");
        }

        $sortie->getParticipants()->add($participant);
        $em->persist($sortie);
        $em->flush();

        $this->addFlash('success', 'Vous êtes inscrit à la sortie "'.$sortie->getNom().'"');
        return $this->redirectToRoute("home");
    }
    /**
     * @Route("/sortie/unsubscribe/{idSortie}/{idParticipant}", name="sortie_unsubscribe", requirements={"idSortie": "\d+","idParticipant": "\d+"})
     */
    public function unsubscribe($idSortie, $idParticipant, EntityManagerInterface $em): Response
    {
        $particiapantRepo = $this->getDoctrine()->getRepository(Participant::class);
        $participant = $particiapantRepo->find($idParticipant);
        $sortieRepo = $this->getDoctrine()->getRepository(Sortie::class);
        $sortie = $sortieRepo->find($idSortie);
        $sortie->removeParticipant($participant);
        $em->persist($sortie);
        $em->flush();

        $this->addFlash('success', 'Vous vous êtes désinscrit de la sortie "'.$sortie->getNom().'"');
        return $this->redirectToRoute("home");
    }
    /**
     * @Route("/sortie/cancel/{id}", name="sortie_cancel", requirements={"id": "\d+"})
     */
    public function cancel($id, EntityManagerInterface $em): Response
    {
        $etatRepo = $this->getDoctrine()->getRepository(Etat::class);
        $etat = $etatRepo->find(5);
        $sortieRepo = $this->getDoctrine()->getRepository(Sortie::class);
        $sortie = $sortieRepo->find($id);
        $sortie->setEtat($etat);
        $em->persist($sortie);
        $em->flush();

        $this->addFlash('success', 'Vous vous avez annulé la sortie "'.$sortie->getNom().'"');
        return $this->redirectToRoute("home");
    }
    /**
     * @Route("/sortie/publish/{id}", name="sortie_publish", requirements={"id": "\d+"})
     */
    public function publish($id, EntityManagerInterface $em): Response
    {
        $etatRepo = $this->getDoctrine()->getRepository(Etat::class);
        $etat = $etatRepo->find(2);
        $sortieRepo = $this->getDoctrine()->getRepository(Sortie::class);
        $sortie = $sortieRepo->find($id);
        $sortie->setEtat($etat);
        $em->persist($sortie);
        $em->flush();

        $this->addFlash('success', 'Vous vous avez publié la sortie "'.$sortie->getNom().'"');
        return $this->redirectToRoute("home");
    }
}
