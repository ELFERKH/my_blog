<?php

namespace App\Repository;

use App\Entity\Post;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;
use DoctrineExtensions\Query\Mysql;

/**
 * @method Post|null find($id, $lockMode = null, $lockVersion = null)
 * @method Post|null findOneBy(array $criteria, array $orderBy = null)
 * @method Post[]    findAll()
 * @method Post[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PostRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Post::class);
    }

    /**
     * @method Post[]
     */
    public function findAll()
    {
        /* $dql = 'SELECT P, TIMESTAMPDIFF( YEAR,date_publication,now()) as years
        , TIMESTAMPDIFF( MONTH, date_publication, now()) % 12 as months
        , FLOOR( TIMESTAMPDIFF( DAY, date_publication, now() ) % 30.4375) as days FROM App\Entity\Post P ORDER BY P.date_publication DESC';
        $query = $this->getEntityManager()->createQuery($dql);*/

        /* $qb = $this->getEntityManager()->createQueryBuilder();
        $qb
            ->select(
                'P, TIMESTAMPDIFF( YEAR,date_publication,now()) as years
                , TIMESTAMPDIFF( MONTH, date_publication, now()) % 12 as months
                , FLOOR( TIMESTAMPDIFF( DAY, date_publication, now() ) % 30.4375) as days'
            )
            ->from('Post', 'P')
            ->orderBy('P.date_publication', 'DESC');
        $query = $qb->getQuery();

        return $query->execute();*/

        $conn = $this->getEntityManager()->getConnection();

        $sql = '
        SELECT P.id, P.categorie_id, P.utilisateur_id, P.titre, P.description, P.image, P.date_publication, TIMESTAMPDIFF( YEAR,date_publication,now()) as years
        , TIMESTAMPDIFF( MONTH, date_publication, now()) % 12 as months
        , FLOOR( TIMESTAMPDIFF( DAY, date_publication, now() ) % 30.4375) as days FROM Post P ORDER BY P.date_publication DESC
            ';
        $stmt = $conn->prepare($sql);
        $resultSet = $stmt->executeQuery();

        // returns an array of arrays (i.e. a raw data set)
        return $resultSet->fetchAllAssociative();
        // return $query->execute();
        // return $this->findBy([], ['datePublication' => 'DESC']);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Post $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(Post $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    // /**
    //  * @return Post[] Returns an array of Post objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Post
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
