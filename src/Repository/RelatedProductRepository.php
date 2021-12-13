<?php

namespace App\Repository;

use App\Entity\RelatedProduct;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method RelatedProduct|null find($id, $lockMode = null, $lockVersion = null)
 * @method RelatedProduct|null findOneBy(array $criteria, array $orderBy = null)
 * @method RelatedProduct[]    findAll()
 * @method RelatedProduct[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RelatedProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RelatedProduct::class);
    }

    // /**
    //  * @return RelatedProduct[] Returns an array of RelatedProduct objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?RelatedProduct
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
