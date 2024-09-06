<?php

namespace App\Repository;

use App\Entity\Outing;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Class\Filter;

/**
 * @extends ServiceEntityRepository<Outing>
 */
class OutingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Outing::class);
    }

    public function search(Filter $filter): array
    {
        $qb = $this->createQueryBuilder('o');

        if ($filter->getName()) {
            $qb->andWhere('LOWER(o.outing_name) LIKE :name')
                ->setParameter('name', '%' . strtolower($filter->getName()) . '%');
        }

        if ($filter->getBeginDate()) {
            $qb->andWhere('o.outing_date >= :beginDate')
                ->setParameter('beginDate', $filter->getBeginDate()->format('Y-m-d'));
        }

        if ($filter->getEndDate()) {
            $qb->andWhere('o.outing_date <= :endDate')
                ->setParameter('endDate', $filter->getEndDate()->format('Y-m-d'));
        }

        if ($filter->getCampusId()) {
            $qb->andWhere('o.id_campus = :campusId')
                ->setParameter('campusId', $filter->getCampusId());
        }

        if ($filter->getIsOrganizer()) {
            $qb->andWhere('o.id_organizer = :organizerId')
                ->setParameter('organizerId', $filter->getIsOrganizer());
        }

        if ($filter->getIsParticipant()) {
            $qb->join('o.id_member', 'u')
                ->andWhere('u.id = :userId')
                ->setParameter('userId', $filter->getIsParticipant());
        }

        if ($filter->getNotParticipant()) {
            $qb->leftJoin('o.id_member', 'u')
                ->andWhere('u.id IS NULL OR u.id != :userId')
                ->setParameter('userId', $filter->getNotParticipant());
        }

        if ($filter->getFinishedOutings()) {
            $qb->andWhere('o.outing_date < :now')
                ->setParameter('now', new \DateTime());
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
