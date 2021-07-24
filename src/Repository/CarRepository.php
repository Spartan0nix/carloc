<?php

namespace App\Repository;

use App\Entity\Car;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Car|null find($id, $lockMode = null, $lockVersion = null)
 * @method Car|null findOneBy(array $criteria, array $orderBy = null)
 * @method Car[]    findAll()
 * @method Car[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CarRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Car::class);
    }

    public function findAvailableCar(int $office){
        return $this->createQueryBuilder('c')
            ->andWhere('c.office_id = :office')
            ->setParameter('office', $office)
            ->orderBy('c.daily_price', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }

    public function filterAvailableCar(string $officeId, array | string $brandsIds, array | string $modelsIds, array | string $typesIds, string $fuelId, string $gearboxId){
        $query = $this->createQueryBuilder('c');

        $officeId ? $query->andWhere('c.office_id = :officeId') 
                          ->setParameter('officeId', $officeId)
                          : '';
        $fuelId ? $query->andWhere('c.fuel_id = :fuelId')
                        ->setParameter('fuelId', $fuelId) 
                        : '';
        $brandsIds && $brandsIds != '' ? $query->andWhere('c.brand_id IN (:brandsIds)') 
                         ->setParameter('brandsIds', $brandsIds, \Doctrine\DBAL\Connection::PARAM_STR_ARRAY)
                         : '';
        $modelsIds && $modelsIds != '' ? $query->andWhere('c.modele_id IN (:modelsIds)') 
                          ->setParameter('modelsIds', $modelsIds, \Doctrine\DBAL\Connection::PARAM_STR_ARRAY)
                          : '';
        $gearboxId ? $query->andWhere('c.gearbox_id = :gearboxId')
                           ->setParameter('gearboxId', $gearboxId) 
                           : '';

        return $query->orderBy('c.daily_price', 'ASC')
                     ->setMaxResults(10)
                     ->getQuery()
                     ->getResult()
                     
        ;
    }
}
