<?php

namespace App\Repository;

use App\Entity\Fishing;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Fishing>
 *
 * @method Fishing|null find($id, $lockMode = null, $lockVersion = null)
 * @method Fishing|null findOneBy(array $criteria, array $orderBy = null)
 * @method Fishing[]    findAll()
 * @method Fishing[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FishingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Fishing::class);
    }

    public function add(Fishing $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Fishing $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    // Method for search engine
    public function searchFishing(string $search): array
    {
        $qb = $this->createQueryBuilder('fishing');
        $query = $qb->select('fishing')
            ->where('fishing.fishRace LIKE :search')
            ->setParameter('search', '%'.$search.'%')
            ->getQuery();

        return $query->getResult();
    }

//    /**
//     * @return Fishing[] Returns an array of Fishing objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('f.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Fishing
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
