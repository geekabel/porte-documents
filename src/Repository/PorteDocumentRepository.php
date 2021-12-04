<?php

namespace App\Repository;

use App\Entity\PorteDocument;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PorteDocument|null find($id, $lockMode = null, $lockVersion = null)
 * @method PorteDocument|null findOneBy(array $criteria, array $orderBy = null)
 * @method PorteDocument[]    findAll()
 * @method PorteDocument[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PorteDocumentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PorteDocument::class);
    }

    // /**
    //  * @return PorteDocument[] Returns an array of PorteDocument objects
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
    public function findOneBySomeField($value): ?PorteDocument
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
