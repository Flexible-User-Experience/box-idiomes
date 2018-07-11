<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Query;

/**
 * Class ClassGroupRepository.
 *
 * @category Repository
 */
class ClassGroupRepository extends EntityRepository
{
    /**
     * @return QueryBuilder
     */
    public function getEnabledSortedByCodeQB()
    {
        return $this->createQueryBuilder('cg')
            ->where('cg.enabled = :enabled')
            ->setParameter('enabled', true)
            ->orderBy('cg.code', 'ASC')
        ;
    }

    /**
     * @return Query
     */
    public function getEnabledSortedByCodeQ()
    {
        return $this->getEnabledSortedByCodeQB()->getQuery();
    }

    /**
     * @return array
     */
    public function getEnabledSortedByCode()
    {
        return $this->getEnabledSortedByCodeQ()->getResult();
    }
}
