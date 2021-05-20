<?php

namespace App\Repository;

use App\Entity\Offices;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Offices|null find($id, $lockMode = null, $lockVersion = null)
 * @method Offices|null findOneBy(array $criteria, array $orderBy = null)
 * @method Offices[]    findAll()
 * @method Offices[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OfficesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Offices::class);
    }

    public function searchOffices(String $office) {
        return $this->createQueryBuilder('o')
            ->andWhere('o.street LIKE :val')
            ->setParameter('val', '%'.$office.'%')
            ->orderBy('o.id', 'ASC')
            ->setMaxResults(8)
            ->getQuery()
            ->getResult()
        ;
    }
}
