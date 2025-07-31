<?php

namespace App\Repository;

use App\Entity\Retweet;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Retweet>
 */
class RetweetRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Retweet::class);
    }

    //    /**
    //     * @return Retweet[] Returns an array of Retweet objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('r.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Retweet
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }

    public function findTweetsRetweetByUser(User $user): array
    {
        $entityManager = $this->getEntityManager();
        $query = $entityManager->createQuery("SELECT retweet FROM App\Entity\Retweet retweet JOIN App\Entity\Tweet tweet WITH retweet.tweet = tweet WHERE retweet.user = :user")->setParameter("user", $user);
        return $query->getResult();
    }
}
