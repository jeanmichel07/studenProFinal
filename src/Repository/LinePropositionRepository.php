<?php

namespace App\Repository;

use App\Entity\LineProposition;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method LineProposition|null find($id, $lockMode = null, $lockVersion = null)
 * @method LineProposition|null findOneBy(array $criteria, array $orderBy = null)
 * @method LineProposition[]    findAll()
 * @method LineProposition[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LinePropositionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LineProposition::class);
    }

    // /**
    //  * @return LineProposition[] Returns an array of LineProposition objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('l.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?LineProposition
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
