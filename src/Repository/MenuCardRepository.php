<?php

namespace App\Repository;

use App\Entity\MenuCard;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<MenuCard>
 *
 * @method MenuCard|null find($id, $lockMode = null, $lockVersion = null)
 * @method MenuCard|null findOneBy(array $criteria, array $orderBy = null)
 * @method MenuCard[]    findAll()
 * @method MenuCard[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MenuCardRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MenuCard::class);
    }

    public function save(MenuCard $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(MenuCard $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
