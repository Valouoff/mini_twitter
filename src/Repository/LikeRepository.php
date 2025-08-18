<?php

namespace App\Repository;

use App\Entity\Like;
use App\Entity\Tweet;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Like>
 */
class LikeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Like::class);
    }

    public function findTweetsLikedByUser(User $user): array
    {
        $entityManager = $this->getEntityManager();
        $query = $entityManager->createQuery("SELECT tweet FROM App\Entity\Tweet tweet JOIN App\Entity\Like l WITH l.tweet = tweet WHERE l.user = :user")->setParameter("user", $user);
        return $query->getResult();
    }

    //    /**
    //     * @return Like[] Returns an array of Like objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('l')
    //            ->andWhere('l.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('l.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Like
    //    {
    //        return $this->createQueryBuilder('l')
    //            ->andWhere('l.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }

    public function findByUserOrderedByIdDesc(User $user): array
    {
        return $this->findBy(
            ['user' => $user],
            ['id' => 'DESC']
        );
    }
}
