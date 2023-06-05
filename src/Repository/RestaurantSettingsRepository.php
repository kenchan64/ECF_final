<?php

namespace App\Repository;

use App\Entity\RestaurantSettings;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<RestaurantSettings>
 *
 * @method RestaurantSettings|null find($id, $lockMode = null, $lockVersion = null)
 * @method RestaurantSettings|null findOneBy(array $criteria, array $orderBy = null)
 * @method RestaurantSettings[]    findAll()
 * @method RestaurantSettings[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RestaurantSettingsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RestaurantSettings::class);
    }

    public function save(RestaurantSettings $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(RestaurantSettings $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @throws NonUniqueResultException
     */
    public function getRestaurantSettings(): ?RestaurantSettings
    {
        return $this->createQueryBuilder('rs')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
