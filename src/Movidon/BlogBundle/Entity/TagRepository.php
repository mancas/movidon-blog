<?php

namespace Movidon\BlogBundle\Entity;

use Movidon\FrontendBundle\Entity\CustomEntityRepository;
use Doctrine\ORM\Query\Expr;
use Doctrine\ORM\Query;
use Doctrine\DBAL\Query\QueryBuilder;
use Doctrine\ORM\EntityRepository;

class TagRepository extends CustomEntityRepository
{
    protected $specialFields = array();

    public function findAll($limit = null)
    {
        return $this->findAllDQL($limit)->getResult();
    }

    public function findAllDQL($limit = null)
    {
        $qb = $this->createQueryBuilder('t');
        $qb->select('t');

        $qb->addOrderBy('t.id','ASC');

        if (isset($limit)) {
            $qb->setMaxResults($limit);
        }

        return $qb->getQuery();
    }

    public function findTagsWithPostsCount($limit = null)
    {
        $qb = $this->createQueryBuilder('t');
        $qb->select('t.id', 't.name', 't.slug', 'count(t.id) AS postCount');
        $qb->innerJoin('t.posts', 'p');

        $qb->addOrderBy('t.name','ASC');
        $qb->groupBy('t.id');

        if (isset($limit)) {
            $qb->setMaxResults($limit);
        }

        return $qb->getQuery()->getResult();
    }
}
