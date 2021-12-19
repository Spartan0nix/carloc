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

    public function filterAvailableCar(string $office_id, array $filter_data){
        // Find car not used by a rent
        $query = $this->createQueryBuilder('c')
                      ->leftJoin('c.rents', 'r')
                      ->andWhere('r.car_id is NULL')
                      ->andWhere('c.office_id = :office_id')
                      ->setParameter('office_id', $office_id);
        
        if(isset($filter_data['fuel_id']) && !empty($filter_data['fuel_id'])) {
            $query->andWhere('c.fuel_id = :fuel_id')
                  ->setParameter('fuel_id', $filter_data['fuel_id'][0])
            ;
        }
        if(isset($filter_data['gearbox_id']) && !empty($filter_data['gearbox_id'])) {
            $query->andWhere('c.gearbox_id = :gearbox_id')
                  ->setParameter('gearbox_id', $filter_data['gearbox_id'][0])
            ;
        }
        if(isset($filter_data['brand_id']) && !empty($filter_data['brand_id'])) {
            $query->andWhere('c.brand_id IN (:brand_ids)')
                  ->setParameter('brand_ids', $filter_data['brand_id'], \Doctrine\DBAL\Connection::PARAM_STR_ARRAY)
            ;
        }
        if(isset($filter_data['model_id']) && !empty($filter_data['model_id'])) {
            $query->andWhere('c.model_id IN (:model_ids)')
                  ->setParameter('model_ids', $filter_data['model_id'], \Doctrine\DBAL\Connection::PARAM_STR_ARRAY)
            ;
        }

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
