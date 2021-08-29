<?php

namespace App\Repository\Address;

use App\Entity\Address\Department;
use App\Query\CastAsText;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Department|null find($id, $lockMode = null, $lockVersion = null)
 * @method Department|null findOneBy(array $criteria, array $orderBy = null)
 * @method Department[]    findAll()
 * @method Department[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DepartmentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Department::class);

        $config = $this->getEntityManager()->getConfiguration();
        $config->addCustomStringFunction('TEXT', CastAsText::class);
    }

    public function searchDepartments(string $department) {
        $int = '/^\d+$/';
        $query = $this->createQueryBuilder('d');

        if(preg_match($int, $department)){
            $query->andWhere('TEXT(d.code) LIKE :val');
        } else {
            $query->andWhere('d.name LIKE :val');
        }

        return $query->setParameter('val', '%'.$department.'%')
            ->orderBy('d.name', 'ASC')
            ->setMaxResults(20)
            ->getQuery()
            ->getResult()
        ;
    }
}
