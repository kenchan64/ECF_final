<?php

namespace App\Repository;

use App\Entity\Reservation;
use DateTimeInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Reservation>
 *
 * @method Reservation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Reservation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Reservation[]    findAll()
 * @method Reservation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReservationRepository extends ServiceEntityRepository
{

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Reservation::class);
    }

    public function save(Reservation $reservation): void
    {
        $entityManager = $this->getEntityManager();
        $entityManager->persist($reservation);
        $entityManager->flush();
    }

    /**
     * @throws NonUniqueResultException|NoResultException
     */
    public function findTotalSeatsReservedByCriteria(DateTimeInterface $datetime): ?int
    {
        $from = clone $datetime;
        $from->setTime((int)$datetime->format('H'), (int)$datetime->format('i'));

        $to = clone $datetime;
        $to->setTime((int)$datetime->format('H') + 1, (int)$datetime->format('i')); // Assuming each reservation lasts 1 hour

        $qb = $this->createQueryBuilder('r');
        $qb->select('SUM(r.nbCouverts)')
            ->where('r.date >= :from')
            ->andWhere('r.date < :to')
            ->setParameters(['from' => $from, 'to' => $to]);


        $result = $qb->getQuery()->getSingleScalarResult();

        return $result !== null ? (int)$result : 0;
    }
}
