<?php

namespace App\Repository\Components;

use App\Entity\Components\Color;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Color|null find($id, $lockMode = null, $lockVersion = null)
 * @method Color|null findOneBy(array $criteria, array $orderBy = null)
 * @method Color[]    findAll()
 * @method Color[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ColorRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Color::class);
    }

    public function searchColors(string $color) {
        return $this->createQueryBuilder('c')
            ->andWhere('c.color LIKE :val')
            ->setParameter('val', '%'.$color.'%')
            ->orderBy('c.color', 'ASC')
            ->setMaxResults(20)
            ->getQuery()
            ->getResult()
        ;
    }
}
