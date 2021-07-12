<?php

namespace App\Repository\Components;

use App\Entity\Components\Gearbox;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Gearbox|null find($id, $lockMode = null, $lockVersion = null)
 * @method Gearbox|null findOneBy(array $criteria, array $orderBy = null)
 * @method Gearbox[]    findAll()
 * @method Gearbox[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GearboxRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Gearbox::class);
    }

    public function searchGearboxs(String $gearbox) {
        return $this->createQueryBuilder('g')
            ->andWhere('g.gearbox LIKE :val')
            ->setParameter('val', '%'.$gearbox.'%')
            ->orderBy('g.gearbox', 'ASC')
            ->setMaxResults(20)
            ->getQuery()
            ->getResult()
        ;
    }
}
