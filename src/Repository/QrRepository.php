<?php

namespace App\Repository;

use App\Entity\Qr;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Qr|null find($id, $lockMode = null, $lockVersion = null)
 * @method Qr|null findOneBy(array $criteria, array $orderBy = null)
 * @method Qr[]    findAll()
 * @method Qr[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class QrRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Qr::class);
    }

    // /**
    //  * @return Qr[] Returns an array of Qr objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('q')
            ->andWhere('q.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('q.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Qr
    {
        return $this->createQueryBuilder('q')
            ->andWhere('q.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
