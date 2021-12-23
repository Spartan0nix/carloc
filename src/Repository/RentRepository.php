<?php

namespace App\Repository;

use App\Entity\Rent;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Rent|null find($id, $lockMode = null, $lockVersion = null)
 * @method Rent|null findOneBy(array $criteria, array $orderBy = null)
 * @method Rent[]    findAll()
 * @method Rent[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Rent::class);
    }

    public function findCancelableRent(string $user_id) {
        return new ArrayCollection($this->createQueryBuilder('r')
            ->andWhere('r.status_id NOT IN (4,5)')
            ->andWhere('r.pickup_date > CURRENT_DATE()')
            ->andWhere('r.user_id = :user_id')
            ->setParameter('user_id', $user_id)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult())
        ;
    }
}