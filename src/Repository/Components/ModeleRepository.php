<?php

namespace App\Repository\Components;

use App\Entity\Components\Modele;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Modele|null find($id, $lockMode = null, $lockVersion = null)
 * @method Modele|null findOneBy(array $criteria, array $orderBy = null)
 * @method Modele[]    findAll()
 * @method Modele[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ModeleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Modele::class);
    }

    public function searchModels(String $model) {
        return $this->createQueryBuilder('m')
            ->andWhere('m.modele LIKE :val')
            ->setParameter('val', '%'.$model.'%')
            ->orderBy('m.modele', 'ASC')
            ->setMaxResults(20)
            ->getQuery()
            ->getResult()
        ;
    }  
}
