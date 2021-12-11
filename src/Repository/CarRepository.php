<?php

namespace App\Repository;

use App\Entity\Car;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\ResultSetMapping;
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
            ->leftJoin('c.rents', 'r')
            ->andWhere('r.car_id is NULL')
            ->andWhere('c.office_id = :office_id')
            ->setParameter('office_id', $office)
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }

    public function filterAvailableCar(string $officeId, array | string $brandsIds, array | string $modelsIds, array | string $typesIds, string $fuelId, string $gearboxId){
        $query = $this->createQueryBuilder('c')
                      ->leftJoin('c.rents', 'r')
                      ->andWhere('r.car_id is NULL');

        $officeId ? $query->andWhere('c.office_id = :officeId') 
                          ->setParameter('officeId', $officeId)
                          : '';
        $fuelId ? $query->andWhere('c.fuel_id = :fuelId')
                        ->setParameter('fuelId', $fuelId) 
                        : '';
        $brandsIds && $brandsIds != '' ? $query->andWhere('c.brand_id IN (:brandsIds)') 
                         ->setParameter('brandsIds', $brandsIds, \Doctrine\DBAL\Connection::PARAM_STR_ARRAY)
                         : '';
        $modelsIds && $modelsIds != '' ? $query->andWhere('c.model_id IN (:modelsIds)') 
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

    public function searchCars(String $car) {
        return $this->createQueryBuilder('c')
            ->innerJoin('c.brand_id', 'b')
            ->innerJoin('c.model_id', 'm')
            ->orWhere('b.brand LIKE :val')
            ->orWhere('m.model LIKE :val')
            ->setParameter('val', '%'.$car.'%')
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(8)
            ->getQuery()
            ->getResult()
        ;
    }
}
