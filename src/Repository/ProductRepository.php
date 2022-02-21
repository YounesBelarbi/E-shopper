<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    /**
     * returns the number of products in the shop database
     *
     * @return int
     */
    public function getNumberOfProduct()
    {
        $queryBuilder = $this->createQueryBuilder('p')
            ->select('COUNT(p)');

        return $queryBuilder->getQuery()->getSingleScalarResult();
    }

    /**
     * return the product list pagination
     *
     * @param int $page
     * @param int $productPerPage
     * @return Paginator
     */
    public function getProducts($page, $productPerPage)
    {
        $firstresult = ($page - 1) * $productPerPage;

        $queryBuilder = $this->createQueryBuilder('p')
            ->setFirstResult($firstresult)
            ->setMaxResults($productPerPage);

        $query = $queryBuilder->getQuery();
        $paginator = new Paginator($query);
        return $paginator;
    }
}
