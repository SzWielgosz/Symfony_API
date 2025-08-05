<?php

namespace App\Repository;

use App\Entity\Symphony;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Symphony>
 *
 * @method Symphony|null find($id, $lockMode = null, $lockVersion = null)
 * @method Symphony|null findOneBy(array $criteria, array $orderBy = null)
 * @method Symphony[]    findAll()
 * @method Symphony[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SymphonyRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Symphony::class);
    }

//    /**
//     * @return Symphony[] Returns an array of Symphony objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('s.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Symphony
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
