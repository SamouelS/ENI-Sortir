<?php

namespace App\Repository;

use App\Entity\Sortie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Security;

/**
 * @method Sortie|null find($id, $lockMode = null, $lockVersion = null)
 * @method Sortie|null findOneBy(array $criteria, array $orderBy = null)
 * @method Sortie[]    findAll()
 * @method Sortie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SortieRepository extends ServiceEntityRepository
{
    private $user;
    public function __construct(ManagerRegistry $registry, Security $security)
    {
        parent::__construct($registry, Sortie::class);
        $this->user = $security->getUser();
    }
    public function findAllOrderByCampus(){
        $query = $this->createQueryBuilder('s')
            ->join('s.campus','c')
            ->addOrderBy('c.nom')
            ->addSelect('c')     
        ;
        return $query->getQuery()->getResult();
    }

    public function findByFilter($sortieFilter){
        dump($sortieFilter);
        //die;
        $query = $this->createQueryBuilder('s')
            ->join('s.campus','c')
            ->leftJoin('s.participants','p')
            ->join('s.etat','e')
            ->addOrderBy('c.nom')
            ->addSelect('c')   
            ->addSelect('p')  
            //->addSelect('e')
        ;
        if (!empty( $sortieFilter->getCampus()) ) {
            $query->andWhere('c = :campus')
                ->setParameter('campus', $sortieFilter->getCampus())
            ;
        }
        if (!empty( $sortieFilter->getLike() ) ) {
            $query->andWhere('s.nom LIKE :like')
                ->setParameter('like', '%'.$sortieFilter->getLike().'%')
            ;
        }
        if (!empty( $sortieFilter->getDateDebut() ) ) {

            $query->andWhere('s.dateHeureDebut >= :dateDebut')
                ->setParameter('dateDebut', $sortieFilter->getDateDebut())
            ;
        }
        if (!empty( $sortieFilter->getDateFin() ) ) {
            $query->andWhere('s.dateHeureDebut <= :dateFin')
                ->setParameter('dateFin', $sortieFilter->getDateFin())
            ;
        }

        if ($sortieFilter->getEtreOrganisateur() ) {
            $query->andWhere('s.organisateur = :user')
                ->setParameter('user', $this->user)
            ;
        }
        if ($sortieFilter->getEtreInscrit() ) {
            $query->andWhere('p = :user')
                ->setParameter('user', $this->user)
            ;
        }
        if ($sortieFilter->getPasInscrit() ) {
            dump('3');
            $query2 = $this->createQueryBuilder('s2')
                ->select('s2.id')
                ->join('s2.participants', 'p2')
                ->where('p2 = :unParticipant');

            $query->andWhere($query->expr()->notIn('s.id', $query2->getDQL()))
                ->setParameter('unParticipant', $this->user)
            ;


            
        }
        if ($sortieFilter->getPasser() ) {
            $query->andWhere('e.id = 4');
        }
        dump($query->getParameters());
        dump($query->getDQL());
        return $query->getQuery()->getResult();
    }

    // /**
    //  * @return Sortie[] Returns an array of Sortie objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Sortie
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
