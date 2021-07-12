<?php

namespace App\Repository\Components;

use App\Entity\Components\Fuel;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Fuel|null find($id, $lockMode = null, $lockVersion = null)
 * @method Fuel|null findOneBy(array $criteria, array $orderBy = null)
 * @method Fuel[]    findAll()
 * @method Fuel[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FuelRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Fuel::class);
    }

    public function searchFuels(String $fuel) {
        return $this->createQueryBuilder('f')
            ->andWhere('f.fuel LIKE :val')
            ->setParameter('val', '%'.$fuel.'%')
            ->orderBy('f.fuel', 'ASC')
            ->setMaxResults(20)
            ->getQuery()
            ->getResult()
        ;
    } 
}
