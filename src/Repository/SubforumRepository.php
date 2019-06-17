<?php

namespace App\Repository;

use App\Entity\Subforum;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Subforum|null find($id, $lockMode = null, $lockVersion = null)
 * @method Subforum|null findOneBy(array $criteria, array $orderBy = null)
 * @method Subforum[]    findAll()
 * @method Subforum[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SubforumRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Subforum::class);
    }

    // /**
    //  * @return Subforum[] Returns an array of Subforum objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Subforum
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
