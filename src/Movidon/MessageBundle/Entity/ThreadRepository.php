<?php

namespace Movidon\MessageBundle\Entity;

use Movidon\FrontendBundle\Entity\CustomEntityRepository;
use Doctrine\ORM\Query\Expr;
use Doctrine\ORM\Query;
use Doctrine\DBAL\Query\QueryBuilder;
use Doctrine\ORM\EntityRepository;

class ThreadRepository extends CustomEntityRepository
{
    protected $specialFields = array();

    public function findAll($limit = null)
    {
        return $this->findAllDQL($limit)->getResult();
    }

    public function findAllDQL($user, $limit = null)
    {
        $qb = $this->createQueryBuilder('t');
        $qb->select('t');
        $qb->leftJoin('t.participants', 'u');

        $qb->addOrderBy('t.updateDate','DESC');

        $and = $qb->expr()->andX();

        $and->add($qb->expr()->eq('u.id', $user->getId()));

        $qb->where($and);

        if (isset($limit)) {
            $qb->setMaxResults($limit);
        }

        return $qb->getQuery();
    }
}
