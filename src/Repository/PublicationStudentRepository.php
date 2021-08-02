<?php

namespace App\Repository;

use App\Entity\PublicationStudent;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PublicationStudent|null find($id, $lockMode = null, $lockVersion = null)
 * @method PublicationStudent|null findOneBy(array $criteria, array $orderBy = null)
 * @method PublicationStudent[]    findAll()
 * @method PublicationStudent[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PublicationStudentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PublicationStudent::class);
    }

    // /**
    //  * @return PublicationStudent[] Returns an array of PublicationStudent objects
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
    public function findOneBySomeField($value): ?PublicationStudent
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
    public function publicationInTreament($us)
    {
        $dql = $this->getEntityManager();

        $query = $dql->createQuery("select pr from App\Entity\Proposition pr LEFT JOIN App\Entity\PublicationStudent p WITH p.id=pr.PublicationStudent LEFT JOIN App\Entity\LineProposition lp WITH lp.Proposition=p.id where  p.state= :stateone and lp.User= :idUs")
                    ->setParameters([
                        "stateone"=>2,
                        "idUs"=>$us
                    ]);
        return $query->getResult();

    }
}
