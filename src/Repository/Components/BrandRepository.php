<?php

namespace App\Repository\Components;

use App\Entity\Components\Brand;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Brand|null find($id, $lockMode = null, $lockVersion = null)
 * @method Brand|null findOneBy(array $criteria, array $orderBy = null)
 * @method Brand[]    findAll()
 * @method Brand[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BrandRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Brand::class);
    }

    public function searchBrands(String $brand) {
        return $this->createQueryBuilder('b')
            ->andWhere('b.brand LIKE :val')
            ->setParameter('val', '%'.$brand.'%')
            ->orderBy('b.brand', 'ASC')
            ->setMaxResults(20)
            ->getQuery()
            ->getResult()
        ;
    }    
}
