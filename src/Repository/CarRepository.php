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
        $rsm = new ResultSetMapping();
        $rsm->addEntityResult('App\Entity\Car', 'c');
        $rsm->addFieldResult('c', 'id', 'id');
        $rsm->addFieldResult('c', 'horsepower', 'horsepower');
        $rsm->addFieldResult('c', 'daily_price', 'daily_price');
        $rsm->addFieldResult('c', 'release_year', 'release_year');
        $rsm->addMetaResult('c', 'fuel_id', 'fuel_id');
        $rsm->addMetaResult('c', 'brand_id', 'brand_id');
        $rsm->addMetaResult('c', 'model_id', 'model_id');
        $rsm->addMetaResult('c', 'color_id', 'color_id');
        $rsm->addMetaResult('c', 'gearbox_id', 'gearbox_id');

        $query = $this->getEntityManager()->createNativeQuery(
            'SELECT c.id, 
                    c.horsepower, 
                    c.daily_price, 
                    c.release_year, 
                    f.id AS fuel_id, 
                    b.id AS brand_id, 
                    m.id AS model_id, 
                    col.id AS color_id, 
                    g.id AS gearbox_id
            FROM car c
            INNER JOIN fuel f ON c.fuel_id = f.id 
            INNER JOIN brand b ON c.brand_id = b.id 
            INNER JOIN model m ON c.model_id = m.id 
            INNER JOIN color col ON c.color_id = col.id 
            INNER JOIN gearbox g ON c.gearbox_id = g.id 
            INNER JOIN Rent r ON c.id != r.car_id 
            WHERE c.office_id = :office_id
            ORDER BY c.daily_price ASC
            LIMIT 10'
        , $rsm);

        $query->setParameter('office_id', $office);
        return $query->getResult();
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
}
