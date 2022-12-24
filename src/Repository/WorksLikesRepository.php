<?php

namespace App\Repository;

use App\Entity\WorksLikes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<WorksLikes>
 *
 * @method WorksLikes|null find($id, $lockMode = null, $lockVersion = null)
 * @method WorksLikes|null findOneBy(array $criteria, array $orderBy = null)
 * @method WorksLikes[]    findAll()
 * @method WorksLikes[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WorksLikesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, WorksLikes::class);
    }

    public function save(WorksLikes $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(WorksLikes $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

        public function getWorksLikedByUser($userId, $id_post): array
    {
            return $this->createQueryBuilder('w')
            ->where('w.user = :userId')
            ->setParameter('userId', $userId)
            ->andWhere('w.likes = :idWorks')
            ->setParameter('idWorks', $id_post)
            ->getQuery()
            ->getResult();
    }

//    /**
//     * @return WorksLikes[] Returns an array of WorksLikes objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('w')
//            ->andWhere('w.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('w.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?WorksLikes
//    {
//        return $this->createQueryBuilder('w')
//            ->andWhere('w.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }

}
