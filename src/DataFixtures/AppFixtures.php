<?php

namespace App\DataFixtures;

use DateTime;
use DateInterval;
use App\Entity\Campus;
use App\Entity\Sortie;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $em)
    {
        $date = new DateTime();    
        for ($i=0; $i < 20 ; $i++) { 
            $sortie = new Sortie;
            $campus = new Campus();
            $campus->setNom("Campus n°$i");
            $sortie->setNom("Sortie n°$i")
                ->setDateHeureDebut($date)
                ->setDuree(\DateTime::createFromFormat('H:i',"$i:00"))
                ->setDateLimiteInscription($date->add(new DateInterval("P1D")))
                ->setNbInscriptionsMax($i)
                ->setInfosSortie("info n°$i")
                ->setCampus($campus)
            ;
            $em->persist($sortie);
        }
        $em->flush();
    }
}
