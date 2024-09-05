<?php

namespace App\Repository;

use App\Entity\Outing;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Outing>
 */
class OutingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Outing::class);
    }

    public function search(?string $name, ?\DateTimeInterface $beginDate, ?\DateTimeInterface $endDate, ?Int $campusId): array
    {
        $qb = $this->createQueryBuilder('o');

        if ($name) {
            $qb->andWhere('LOWER(o.outing_name) LIKE :name')
                ->setParameter('name', '%' . strtolower($name) . '%');
        }

        if ($beginDate) {
            $qb->andWhere('o.outing_date >= :beginDate')
                ->setParameter('beginDate', $beginDate->format('Y-m-d'));
        }

        if ($endDate) {
            $qb->andWhere('o.outing_date <= :endDate')
                ->setParameter('endDate', $endDate->format('Y-m-d'));
        }

        if ($campusId) {
            $qb->andWhere('o.id_campus = :campusId')
                ->setParameter('campusId', $campusId);
        }

        return $qb->getQuery()->getResult();
    }

    public function outingWhereIAmOrganizer(int $outingId, int $userId): array
    {
        $qb = $this->createQueryBuilder('o');

        $qb->select('o')
            ->andWhere('o.id = :outingId')
            ->andWhere('o.id_organizer = :userId')
            ->setParameter('outingId', $outingId)
            ->setParameter('userId', $userId);

        return $qb->getQuery()->getResult();
    }

    public function findRegisteredUsersForOuting(Outing $outing): array
    {
        return $this->createQueryBuilder('o')
            ->select('u')
            ->join('o.id_member', 'u')
            ->where('o.id = :outingId')
            ->setParameter('outingId', $outing->getId())
            ->getQuery()
            ->getResult();
    }

    public function findOutingsWithRegisteredUsers(): array
    {
        return $this->createQueryBuilder('o')
            ->select('o', 'u')
            ->leftJoin('o.id_member', 'u')
            ->getQuery()
            ->getResult();
    }

    public function finishedOutings(): array
    {
        $qb = $this->createQueryBuilder('o');

        $qb->select('o')
            ->andWhere('o.outing_date > :now')
            ->setParameter('now', new \DateTime());

        return $qb->getQuery()->getResult();
    }




    //    /**
    //     * @return Outing[] Returns an array of Outing objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('o')
    //            ->andWhere('o.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('o.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Outing
    //    {
    //        return $this->createQueryBuilder('o')
    //            ->andWhere('o.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
