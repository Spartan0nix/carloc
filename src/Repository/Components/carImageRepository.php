<?php

namespace App\Repository\Components;

use App\Entity\Components\carImage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method carImage|null find($id, $lockMode = null, $lockVersion = null)
 * @method carImage|null findOneBy(array $criteria, array $orderBy = null)
 * @method carImage[]    findAll()
 * @method carImage[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class carImageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, carImage::class);
    }

    // /**
    //  * @return carImage[] Returns an array of carImage objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?carImage
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
