<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Query;

/**
 * Class SpendingCategoryRepository.
 *
 * @category Repository
 */
class SpendingCategoryRepository extends EntityRepository
{
    /**
     * @return QueryBuilder
     */
    public function getEnabledSortedByNameQB()
    {
        return $this->createQueryBuilder('sc')
            ->where('sc.enabled = :enabled')
            ->setParameter('enabled', true)
            ->orderBy('sc.name', 'ASC')
            ;
    }

    /**
     * @return Query
     */
    public function getEnabledSortedByNameQ()
    {
        return $this->getEnabledSortedByNameQB()->getQuery();
    }

    /**
     * @return array
     */
    public function getEnabledSortedByName()
    {
        return $this->getEnabledSortedByNameQ()->getResult();
    }
}
