<?php

namespace App\Repository;

use App\Entity\Reservation;
use DateTime;
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

    public function getTotalGuestsForTime(?DateTime $heure, ?DateTime $date): int
    {
        $query = $this->getEntityManager()->createQuery(
            'SELECT SUM(r.nbCouverts)
        FROM App\Entity\Reservation r
        WHERE r.heure = :heure
        AND r.date = :date'
        )->setParameter('heure', $heure->format('H:i:s'))
            ->setParameter('date', $date->format('Y-m-d'));

        try {
            $result = $query->getSingleScalarResult();
            return $result ?? 0;
        } catch (NoResultException|NonUniqueResultException) {
            return 0;
        }
    }
}
