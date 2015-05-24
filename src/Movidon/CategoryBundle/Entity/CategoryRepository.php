<?php

namespace Movidon\CategoryBundle\Entity;

use Movidon\FrontendBundle\Entity\CustomEntityRepository;
use Doctrine\ORM\Query\Expr;
use Doctrine\ORM\Query;
use Doctrine\DBAL\Query\QueryBuilder;
use Doctrine\ORM\EntityRepository;

class CategoryRepository extends CustomEntityRepository
{
    protected $specialFields = array();

    public function findAll($limit = null) {
        return $this->findAllDQL($limit)->getResult();
    }

    public function findAllDQL($limit = null)
    {
        $qb = $this->createQueryBuilder('c');
        $qb->select('c');

        $qb->addOrderBy('c.id','ASC');

        if (isset($limit)) {
            $qb->setMaxResults($limit);
        }

        return $qb->getQuery();
    }

    public function findSEOCategories($limit = null)
    {
        $qb = $this->createQueryBuilder('c');
        $qb->select('c');

        $qb->addOrderBy('c.updated','DESC');

        $and = $qb->expr()->andx();

        $and->add($qb->expr()->eq('c.useInIndex', true));

        $qb->where($and);

        if (isset($limit)) {
            $qb->setMaxResults($limit);
        }

        return $qb->getQuery()->getResult();
    }
}
