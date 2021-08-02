<?php

namespace App\Repository;

use App\Entity\PublicationTraited;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PublicationTraited|null find($id, $lockMode = null, $lockVersion = null)
 * @method PublicationTraited|null findOneBy(array $criteria, array $orderBy = null)
 * @method PublicationTraited[]    findAll()
 * @method PublicationTraited[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PublicationTraitedRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PublicationTraited::class);
    }

    // /**
    //  * @return PublicationTraited[] Returns an array of PublicationTraited objects
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
    public function findOneBySomeField($value): ?PublicationTraited
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
