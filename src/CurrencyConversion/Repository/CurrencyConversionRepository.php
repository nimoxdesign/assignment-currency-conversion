<?php

namespace App\CurrencyConversion\Repository;

use App\CurrencyConversion\Entity\CurrencyConversion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CurrencyConversion|null find($id, $lockMode = null, $lockVersion = null)
 * @method CurrencyConversion|null findOneBy(array $criteria, array $orderBy = null)
 * @method CurrencyConversion[]    findAll()
 * @method CurrencyConversion[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CurrencyConversionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CurrencyConversion::class);
    }

    public function createEntity()
    {
        return new CurrencyConversion;
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(CurrencyConversion $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(CurrencyConversion $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    public function FindOneBy(array $criteria, $orderBy = null): ?CurrencyConversion
    {
        if (count($criteria)) {
            $queryBuilder = $this->createQueryBuilder('c');

            foreach ($criteria as $field => $data) {
                $evaluation = array_key_exists('evaluation', $data) 
                    ? $data['evaluation'] 
                    : '=';

                $queryBuilder
                    ->andWhere("c.{$field} {$evaluation} :{$field}")
                    ->setParameter($field, $data['value'])
                ;
            }

            return $queryBuilder->getQuery()->getOneOrNullResult();
        }

        return null;
    }

    // /**
    //  * @return CurrencyConversion[] Returns an array of CurrencyConversion objects
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
    public function findOneBySomeField($value): ?CurrencyConversion
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
