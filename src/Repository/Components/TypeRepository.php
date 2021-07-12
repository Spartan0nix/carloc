<?php

namespace App\Repository\Components;

use App\Entity\Components\Type;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Type|null find($id, $lockMode = null, $lockVersion = null)
 * @method Type|null findOneBy(array $criteria, array $orderBy = null)
 * @method Type[]    findAll()
 * @method Type[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TypeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Type::class);
    }

    public function searchTypes(String $type) {
        return $this->createQueryBuilder('t')
            ->andWhere('t.type LIKE :val')
            ->setParameter('val', '%'.$type.'%')
            ->orderBy('t.type', 'ASC')
            ->setMaxResults(20)
            ->getQuery()
            ->getResult()
        ;
    } 
}
