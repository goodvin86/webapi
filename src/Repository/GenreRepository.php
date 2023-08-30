<?php

namespace App\Repository;

use App\Entity\Genre;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Genre>
 *
 * @method Genre|null find($id, $lockMode = null, $lockVersion = null)
 * @method Genre|null findOneBy(array $criteria, array $orderBy = null)
 * @method Genre[]    findAll()
 * @method Genre[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GenreRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Genre::class);
    }

    public function save(Genre $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Genre $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findAllGenres(): array
    {
        return $this->createQueryBuilder('genre')
            ->select('genre.id, genre.name')
            ->getQuery()
            ->getResult();
    }

    public function findGamesWithThisGenre($id): array
    {
        return $this->createQueryBuilder('genre')
            ->select('game.name')
            ->innerJoin('genre.games', 'game')
            ->where('genre.id=:val')
            ->setParameter('val', $id)
            ->getQuery()
            ->getResult();
    }

    public function findGenresWithThisGame($id): array
    {
        return $this->createQueryBuilder('genre')
            ->select('genre.id')
            ->innerJoin('genre.games', 'game')
            ->where('game.id=:val')
            ->setParameter('val', $id)
            ->getQuery()
            ->getResult();
    }

    public function findGenreById($id): array
    {
        return $this->createQueryBuilder('genre')
            ->select('genre.id','genre.name')
            ->where('genre.id=:val')
            ->setParameter('val', $id)
            ->getQuery()
            ->getResult();
    }
}
