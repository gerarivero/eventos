<?php

namespace App\Repository;

use App\Entity\InscripcionCongreso;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method InscripcionCongreso|null find($id, $lockMode = null, $lockVersion = null)
 * @method InscripcionCongreso|null findOneBy(array $criteria, array $orderBy = null)
 * @method InscripcionCongreso[]    findAll()
 * @method InscripcionCongreso[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InscripcionCongresoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, InscripcionCongreso::class);
    }

    // /**
    //  * @return InscripcionCongreso[] Returns an array of InscripcionCongreso objects
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
    public function findOneBySomeField($value): ?InscripcionCongreso
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function findByPersonaCondicionCongreso($persona, $condicion, $congreso)
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
}
