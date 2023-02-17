<?php

namespace App\Repository;

use App\Entity\RecipeCourse;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<RecipeCourse>
 *
 * @method RecipeCourse|null find($id, $lockMode = null, $lockVersion = null)
 * @method RecipeCourse|null findOneBy(array $criteria, array $orderBy = null)
 * @method RecipeCourse[]    findAll()
 * @method RecipeCourse[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RecipeCourseRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RecipeCourse::class);
    }

    public function save(RecipeCourse $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(RecipeCourse $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return RecipeCourse[] Returns an array of RecipeCourse objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('r.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?RecipeCourse
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
