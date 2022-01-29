<?php

namespace App\Repository;

use App\Entity\SaisieAdresse;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method SaisieAdresse|null find($id, $lockMode = null, $lockVersion = null)
 * @method SaisieAdresse|null findOneBy(array $criteria, array $orderBy = null)
 * @method SaisieAdresse[]    findAll()
 * @method SaisieAdresse[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SaisieAdresseRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SaisieAdresse::class);
    }

    // /**
    //  * @return SaisieAdresse[] Returns an array of SaisieAdresse objects
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
    public function findOneBySomeField($value): ?SaisieAdresse
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
