<?php

namespace App\Repository;

use App\Entity\Inscripcion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Inscripcion|null find($id, $lockMode = null, $lockVersion = null)
 * @method Inscripcion|null findOneBy(array $criteria, array $orderBy = null)
 * @method Inscripcion[]    findAll()
 * @method Inscripcion[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InscripcionRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Inscripcion::class);
    }

    // /**
    //  * @return Inscripcion[] Returns an array of Inscripcion objects
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

    public function findByCursoEstadoInscripto($curso, $estado)
    {        
        if ($estado != "Todos" && $estado != null){
            return $this->createQueryBuilder('i')
            ->leftJoin('i.curso', 'c')
            ->where('i.estado = :estado')
            ->andWhere('c.id = :curso')
            ->setParameter('estado', $estado)
            ->setParameter('curso', $curso)
            ->orderBy('i.id', 'ASC')            
            ->getQuery()
            ->getResult()
        ;
        } else if ($estado == null || $estado == "Todos"){            
            return $this->createQueryBuilder('i')
            ->leftJoin('i.curso', 'c')            
            ->andWhere('c.id = :curso')            
            ->setParameter('curso', $curso)
            ->orderBy('i.id', 'ASC')            
            ->getQuery()
            ->getResult()
            ;    
        }        
    }
}
