<?php

namespace App\Repository;

use App\Entity\InscriptoCongreso;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method InscriptoCongreso|null find($id, $lockMode = null, $lockVersion = null)
 * @method InscriptoCongreso|null findOneBy(array $criteria, array $orderBy = null)
 * @method InscriptoCongreso[]    findAll()
 * @method InscriptoCongreso[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InscriptoCongresoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, InscriptoCongreso::class);
    }

    // /**
    //  * @return InscriptoCongreso[] Returns an array of InscriptoCongreso objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('i.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?InscriptoCongreso
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
