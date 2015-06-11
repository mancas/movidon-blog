<?php

namespace Movidon\BackendBundle\Entity;

use Movidon\FrontendBundle\Entity\CustomEntityRepository;
use Doctrine\ORM\Query\Expr;
use Doctrine\ORM\Query;
use Doctrine\DBAL\Query\QueryBuilder;
use Doctrine\ORM\EntityRepository;

class AdminUserRepository extends CustomEntityRepository
{
    protected $specialFields = array();

    public function findAll($limit = null)
    {
        return $this->findAllDQL($limit)->getResult();
    }

    public function findAllDQL($limit = null)
    {
        $qb = $this->createQueryBuilder('u');
        $qb->select('u');

        $qb->addOrderBy('u.id','ASC');

        if (isset($limit)) {
            $qb->setMaxResults($limit);
        }

        return $qb->getQuery();
    }

    public function findAllExcept($user, $limit = null)
    {
        $qb = $this->createQueryBuilder('u');
        $qb->select('u');

        $qb->addOrderBy('u.id','ASC');
        $and = $qb->expr()->andX();

        $and->add($qb->expr()->neq('u.id', '\'' . $user->getId() . '\''));

        if (isset($limit)) {
            $qb->setMaxResults($limit);
        }

        return $qb->getQuery()->getResult();
    }

    public function findAllOrderedByPostCount($limit = null)
    {
        $qb = $this->createQueryBuilder('u');
        $qb->select('u', 'count(u.id) AS HIDDEN postCount');
        $qb->innerJoin('u.posts', 'p');

        $qb->addOrderBy('postCount','DESC');
        $qb->groupBy('u.id');

        if (isset($limit)) {
            $qb->setMaxResults($limit);
        }

        return $qb->getQuery()->getResult();
    }
}
