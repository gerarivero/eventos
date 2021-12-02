<?php

namespace App\Repository;

use App\Entity\Curso;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Curso|null find($id, $lockMode = null, $lockVersion = null)
 * @method Curso|null findOneBy(array $criteria, array $orderBy = null)
 * @method Curso[]    findAll()
 * @method Curso[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CursoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Curso::class);
    }

    // /**
    //  * @return Curso[] Returns an array of Curso objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Curso
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function findByEstadoInscripto($estado)
    {
        return $this->createQueryBuilder('c')
            ->leftJoin('c.inscripciones', 'i')
            ->andWhere('i.estado = :val')
            ->setParameter('val', $estado)
            ->orderBy('c.id', 'ASC')            
            ->getQuery()
            ->getResult()
        ;
    }
}
