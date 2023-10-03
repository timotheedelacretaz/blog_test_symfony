<?php

namespace App\Repository;

use App\Entity\Article;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Article>
 *
 * @method Article|null find($id, $lockMode = null, $lockVersion = null)
 * @method Article|null findOneBy(array $criteria, array $orderBy = null)
 * @method Article[]    findAll()
 * @method Article[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArticleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Article::class);
    }
    public function findAllArticleById($value): array
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.user_id = :val')
            ->setParameter('val', $value)
            ->orderBy('u.date', 'DESC')
            ->getQuery()
            ->getResult()
            ;
    }

    public function findAllRecommendedArticle(): array
    {
        return $this->createQueryBuilder('u')
            ->where('u.upvote >= 3')
            ->orderBy('u.date', 'DESC')
            ->getQuery()
            ->getResult()
            ;
    }

    public function findAllNotRecommendedArticle(): array
    {
        return $this->createQueryBuilder('u')
            ->where('u.upvote < 3')
            ->orderBy('u.date', 'DESC')
            ->getQuery()
            ->getResult()
            ;
    }
    public function findAllGreaterThanDate($value): array
    {
        return $this->createQueryBuilder('u')
            ->where('u.date > :val')
            ->setParameter('val',$value)
            ->orderBy('u.date', 'ASC')
            ->getQuery()
            ->getResult()
            ;
    }

    public function findArticleBetweenDate($value,$value2): array
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.date BETWEEN :val AND :val2')
            ->setParameter('val', $value)
            ->setParameter('val2',$value2)
            ->orderBy('u.date','ASC')
            ->getQuery()
            ->getResult()
            ;
    }



//    /**
//     * @return Article[] Returns an array of Article objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('a.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Article
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
