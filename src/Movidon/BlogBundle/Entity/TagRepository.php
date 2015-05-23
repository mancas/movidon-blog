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
        return $this->findTagsDQL($limit)->getResult();
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
}
