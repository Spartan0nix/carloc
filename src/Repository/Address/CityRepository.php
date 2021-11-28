<?php

namespace App\Repository\Address;

use App\Entity\Address\City;
use App\Query\CastAsText;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method City|null find($id, $lockMode = null, $lockVersion = null)
 * @method City|null findOneBy(array $criteria, array $orderBy = null)
 * @method City[]    findAll()
 * @method City[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CityRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, City::class);
        
        $config = $this->getEntityManager()->getConfiguration();
        $config->addCustomStringFunction('TEXT', CastAsText::class);
    }

    public function searchCities(string $city, string $required_department_id) {
        $int = '/^\d+$/';
        $query = $this->createQueryBuilder('c');

        $required_department_id != '' 
        ? $query->andWhere('c.department_id = :department_id')
                ->setParameter('department_id', $required_department_id) : '';

        if(preg_match($int, $city)){
            $query->andWhere('TEXT(c.code) LIKE :val');
        } else {
            $query->andWhere('c.name LIKE :val');
        }

        return $query->setParameter('val', '%'.$city.'%')
            ->orderBy('c.name', 'ASC')
            ->setMaxResults(20)
            ->getQuery()
            ->getResult()
        ;
    }

    public function findByNames(array $names): array
    {
        return $this->createQueryBuilder('c')
            ->where('LOWER(c.name) IN (:name)')
            ->setParameter('name', array_map(fn (string $name) => strtolower($name), $names))
            ->getQuery()
            ->getResult();
    }
}
