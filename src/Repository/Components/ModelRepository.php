<?php

namespace App\Repository\Components;

use App\Entity\Components\Model;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Model|null find($id, $lockMode = null, $lockVersion = null)
 * @method Model|null findOneBy(array $criteria, array $orderBy = null)
 * @method Model[]    findAll()
 * @method Model[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ModelRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Model::class);
    }

    public function searchModels(String $model) {
        return $this->createQueryBuilder('m')
            ->andWhere('m.model LIKE :val')
            ->setParameter('val', '%'.$model.'%')
            ->orderBy('m.model', 'ASC')
            ->setMaxResults(20)
            ->getQuery()
            ->getResult()
        ;
    }  
}
