<?php

namespace App\Repository;

use App\Entity\Ecoles;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Ecoles|null find($id, $lockMode = null, $lockVersion = null)
 * @method Ecoles|null findOneBy(array $criteria, array $orderBy = null)
 * @method Ecoles[]    findAll()
 * @method Ecoles[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EcolesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Ecoles::class);
    }

     /**
     * @return Ecoles[] Returns an array of Ecoles objects
      */
    
    public function finddata():array
    {
        return $this->createQueryBuilder('e')
        ->select('e.nom_ecole','e.adresse_ecole','e.code_postale_ecole','e.geo',	'e.type_ecole'  )
            
            ->getQuery()
            ->getResult()
        ;
    }
    

    
    public function findOneBySomeField($value): ?Ecoles
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    
}
