<?php

namespace App\Repository;

use App\Entity\Siren;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Siren|null find($id, $lockMode = null, $lockVersion = null)
 * @method Siren|null findOneBy(array $criteria, array $orderBy = null)
 * @method Siren[]    findAll()
 * @method Siren[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SirenRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Siren::class);
    }

    // /**
    //  * @return Siren[] Returns an array of Siren objects
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
    public function findOneBySomeField($value): ?Siren
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
