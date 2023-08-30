<?php

namespace App\Repository;

use App\Entity\Developer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Developer>
 *
 * @method Developer|null find($id, $lockMode = null, $lockVersion = null)
 * @method Developer|null findOneBy(array $criteria, array $orderBy = null)
 * @method Developer[]    findAll()
 * @method Developer[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DeveloperRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Developer::class);
    }

    public function save(Developer $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Developer $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findAllDevelopers(): array
    {
        return $this->createQueryBuilder('developer')
            ->select('developer.id, developer.name')
            ->getQuery()
            ->getResult();
    }

    public function findGamesWithThisDeveloper($id): array
    {
        return $this->createQueryBuilder('d')
            ->select('g.name')
            ->innerJoin('d.game','g','with', 'g.developer=:val')
            ->setParameter('val', $id)
            ->getQuery()
            ->getResult();
    }

    public function findDeveloperById($id): array
    {
        return $this->createQueryBuilder('developer')
            ->select('developer.id','developer.name')
            ->where('developer.id=:val')
            ->setParameter('val', $id)
            ->getQuery()
            ->getResult();
    }

}
