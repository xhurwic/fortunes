<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Pagerfanta\Adapter\DoctrineORMAdapter;


class FortuneRepository extends \Doctrine\ORM\EntityRepository
{
    public function findLast ()
    {
        $queryBuilder = $this->createQueryBuilder('F')
            ->where('F.published = 1')
            ->orderBy('F.createdAt', 'DESC');
        $adapter = new DoctrineORMAdapter($queryBuilder);
        return $adapter;

       /* $this->createQueryBuilder('F')
                    ->setMaxResults(5)
                    ->orderBy('F.createdAt', 'DESC')
                    ->getQuery()
                    ->getResult();*/
    }

    public function findRated ()
    {
        return $this->createQueryBuilder('F')
            ->setMaxResults(3)
            ->where('F.published = 1')
            ->orderBy('F.upVote - F.downVote', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function findByAuthor($author)
    {
        return $this->createQueryBuilder('F')
            ->setParameter("author", $author)
            ->where("F.author = :author")
            ->andWhere('F.published = 1')
            ->getQuery()
            ->getResult();
    }

    public function findOne ($title)
    {
        return $this->createQueryBuilder('F')
            ->setParameter("title", $title)
            ->where("F.title = :title")
            ->setMaxResults(1)
            ->getQuery()
            ->getSingleResult();
    }

    public function finfList ($author)
    {
        return $this->createQueryBuilder('F')
            ->setParameter("author", $author)
            ->where("F.author = :author")
            ->getQuery()
            ->getResult();
    }
}

