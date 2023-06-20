<?php

namespace App\Repository;

use App\Entity\Reservation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
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
     * @throws NonUniqueResultException
     */
    public function findReservationIdByCriteria(int $nbCouverts, \DateTimeInterface $heure, \DateTimeInterface $date): ?int
    {
        $qb = $this->createQueryBuilder('r');
        $qb->select('r.id')
            ->where('r.nbCouverts = :nbCouverts')
            ->andWhere('r.heure = :heure')
            ->andWhere('r.date = :date')
            ->setParameter('nbCouverts', $nbCouverts)
            ->setParameter('heure', $heure)
            ->setParameter('date', $date)
            ->setMaxResults(1);

        $result = $qb->getQuery()->getOneOrNullResult();

        return $result !== null ? $result['id'] : null;
    }
}
