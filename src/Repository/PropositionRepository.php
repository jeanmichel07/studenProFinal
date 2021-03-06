<?php

namespace App\Repository;

use App\Entity\Proposition;
use App\Entity\PublicationStudent;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Proposition|null find($id, $lockMode = null, $lockVersion = null)
 * @method Proposition|null findOneBy(array $criteria, array $orderBy = null)
 * @method Proposition[]    findAll()
 * @method Proposition[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PropositionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Proposition::class);
    }

    // /**
    //  * @return Proposition[] Returns an array of Proposition objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Proposition
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function propositionWithPrestation()
    {
        $dql = $this->getEntityManager();

        $query = $dql->createQuery("select pr from App\Entity\Proposition pr LEFT JOIN App\Entity\PublicationStudent p WITH p.id=pr.PublicationStudent LEFT JOIN App\Entity\LineProposition lp WITH lp.Proposition=p.id where  p.state NOT IN (:stateone , :statetwo )")
                    ->setParameters([
                        "stateone"=>2,
                        "statetwo"=>3
                    ]);

        return $query->getResult();

    }
}
