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

    public function filterAvailableCar(string $officeId, string $brandId, string $modeleId, string $typeId, string $fuelId, string $gearboxId){
        $query = $this->createQueryBuilder('c');

        $fuelId ? $query->andWhere('c.fuel_id = :fuelId')
                        ->setParameter('fuelId', $fuelId) 
                        : '';
        $brandId ? $query->andWhere('c.brand_id = :brandId') 
                         ->setParameter('brandId', $brandId)
                         : '';
        $modeleId ? $query->andWhere('c.modele_id = :modeleId') 
                          ->setParameter('modeleId', $modeleId)
                          : '';
        $gearboxId ? $query->andWhere('c.gearbox_id = :gearboxId')
                           ->setParameter('gearboxId', $gearboxId) 
                           : '';
        $officeId ? $query->andWhere('c.office_id = :officeId') 
                          ->setParameter('officeId', $officeId)
                          : '';

        return $query->orderBy('c.daily_price', 'ASC')
                     ->setMaxResults(10)
                     ->getQuery()
                     ->getResult()
            // ->andWhere('c.fuel_id = :fuelId')
            // ->andWhere('c.brand_id = :brandId')
            // ->andWhere('c.modele_id = :modeleId')
            // ->andWhere('c.gearbox_id = :gearboxId')
            // ->andWhere('c.office_id = :officeId')
            // ->setParameters(['fuelId' => $fuelId, 'brandId' => $brandId, 'modeleId' => $modeleId, 'gearboxId' => $gearboxId, 'officeId' => $officeId])
            // ->orderBy('c.daily_price', 'ASC')
            // ->setMaxResults(10)
            // ->getQuery()
            // ->getResult()
        ;
    }
}
