<?php

namespace App\Repository;

use App\Entity\Game;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Game>
 *
 * @method Game|null find($id, $lockMode = null, $lockVersion = null)
 * @method Game|null findOneBy(array $criteria, array $orderBy = null)
 * @method Game[]    findAll()
 * @method Game[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GameRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Game::class);
    }

    public function save(Game $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Game $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findAllGame(): array
    {
        return $this->createQueryBuilder('game')
            ->select('game.id','game.name','developer.name developer_name')
            ->addSelect("GROUP_CONCAT(genre.name SEPARATOR ', ') as genre_name")
            ->leftJoin('game.developer','developer')
            ->leftJoin('game.genre','genre')
            ->groupBy('game.id')
            ->orderBy('game.id','ASC')
            ->getQuery()
            ->getResult();
    }

    public function findGamesByGenre($genreId): array
    {
        return $this->createQueryBuilder('game')
            ->select('game.id','game.name','developer.name developer_name')
            ->addSelect("GROUP_CONCAT(genre.name SEPARATOR ', ') as genre_name")
            ->andWhere('genre.id=:val')
            ->leftJoin('game.developer','developer')
            ->leftJoin('game.genre','genre')
            ->setParameter('val', $genreId)
            ->groupBy('game.id')
            ->getQuery()
            ->getResult();
    }

    public function findGameById($id): array
    {
        return $this->createQueryBuilder('game')
            ->select('game.id','game.name','developer.name developer_name')
            ->addSelect("GROUP_CONCAT(genre.name SEPARATOR ', ') as genre_name")
            ->andWhere('game.id=:val')
            ->leftJoin('game.developer','developer')
            ->leftJoin('game.genre','genre')
            ->setParameter('val', $id)
            ->getQuery()
            ->getResult();
    }
}
