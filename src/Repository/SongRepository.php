<?php

namespace App\Repository;

use App\Entity\Song;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Song>
 *
 * @method Song|null find($id, $lockMode = null, $lockVersion = null)
 * @method Song|null findOneBy(array $criteria, array $orderBy = null)
 * @method Song[]    findAll()
 * @method Song[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SongRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Song::class);
    }

    public function add(Song $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Song $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @return Song[] Returns an array of Song objects
     */
    public function findFiveSongs(string $song): array
    {
        return $this->createQueryBuilder('m')
            ->where('m.duration < :duration')
            ->setParameter('duration', $song)
            ->orderBy('m.duration', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    /**
     * @return Song[] Returns an array of Song objects
     */
    // cette fonction nous permet de nous retourner la music la plus longue
    public function findLongestSong(): array
    {
        return $this->createQueryBuilder('m')
            ->orderBy('m.duration', 'DESC')
            ->setMaxResults(5)
            ->getQuery()
            ->getResult()
            ;
    }

    /**
     * @return Song[] Returns an array of Song objects
     */
    public function searchSong(string $searchTitle): array
    {
        return $this->createQueryBuilder('m')
            ->where('m.title LIKE :searchTitle')
            ->setParameters([
                'searchTitle' => '%'. $searchTitle .'%',
            ])
            ->getQuery()
            ->getResult();
    }
}
