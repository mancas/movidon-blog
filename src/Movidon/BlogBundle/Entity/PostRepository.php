<?php

namespace Movidon\BlogBundle\Entity;

use Movidon\FrontendBundle\Entity\CustomEntityRepository;
use Doctrine\ORM\Query\Expr;
use Doctrine\ORM\Query;
use Doctrine\DBAL\Query\QueryBuilder;
use Doctrine\ORM\EntityRepository;

class PostRepository extends CustomEntityRepository
{
    protected $specialFields = array();

    public function findAll($limit = null)
    {
        return $this->findAllDQL($limit)->getResult();
    }

    public function findAllDQL($limit = null)
    {
        $qb = $this->createQueryBuilder('p');
        $qb->select('p');

        $qb->addOrderBy('p.id','ASC');

        if (isset($limit)) {
            $qb->setMaxResults($limit);
        }

        return $qb->getQuery();
    }

    public function findAllBlog($limit = null)
    {
        $qb = $this->createQueryBuilder('p');
        $qb->select('p');

        $qb->addOrderBy('p.updated','DESC');
        $and = $qb->expr()->andX();

        $and->add($qb->expr()->isNotNull('p.published'));
        $and->add($qb->expr()->isNull('p.deleted'));

        $qb->where($and);

        if (isset($limit)) {
            $qb->setMaxResults($limit);
        }

        return $qb->getQuery()->getResult();
    }

    public function findAllByTag($tag, $limit = null)
    {
        return $this->findAllByTagDQL($tag, $limit)->getResult();
    }

    public function findAllByTagDQL($tag, $limit = null)
    {
        $qb = $this->createQueryBuilder('p');
        $qb->select('p');
        $qb->innerJoin('p.tags', 't');

        $qb->addOrderBy('p.updated','DESC');
        $and = $qb->expr()->andX();

        $and->add($qb->expr()->isNotNull('p.published'));
        $and->add($qb->expr()->isNull('p.deleted'));
        $and->add($qb->expr()->eq('t.name', '\'' .$tag->getName() . '\''));

        $qb->where($and);

        if (isset($limit)) {
            $qb->setMaxResults($limit);
        }

        return $qb->getQuery();
    }
}
