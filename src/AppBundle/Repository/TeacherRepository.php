<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Query;

/**
 * Class TeacherRepository.
 *
 * @category Repository
 */
class TeacherRepository extends EntityRepository
{
    /**
     * @return QueryBuilder
     */
    public function findAllEnabledSortedByPositionQB()
    {
        $query = $this
            ->createQueryBuilder('t')
            ->where('t.enabled = :enabled')
            ->andWhere('t.showInHomepage = :showInHomepage')
            ->setParameter('enabled', true)
            ->setParameter('showInHomepage', true)
            ->orderBy('t.position', 'ASC');

        return $query;
    }

    /**
     * @return Query
     */
    public function findAllEnabledSortedByPositionQ()
    {
        return $this->findAllEnabledSortedByPositionQB()->getQuery();
    }

    /**
     * @return array
     */
    public function findAllEnabledSortedByPosition()
    {
        return $this->findAllEnabledSortedByPositionQ()->getResult();
    }

    /**
     * @return QueryBuilder
     */
    public function getEnabledSortedByNameQB()
    {
        return $this->createQueryBuilder('t')
            ->where('t.enabled = :enabled')
            ->andWhere('t.showInHomepage = :showInHomepage')
            ->setParameter('showInHomepage', true)
            ->setParameter('enabled', true)
            ->orderBy('t.name', 'ASC')
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
