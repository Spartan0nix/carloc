<?php

namespace App\Repository;

use App\Entity\Office;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Office|null find($id, $lockMode = null, $lockVersion = null)
 * @method Office|null findOneBy(array $criteria, array $orderBy = null)
 * @method Office[]    findAll()
 * @method Office[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OfficeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Office::class);
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
