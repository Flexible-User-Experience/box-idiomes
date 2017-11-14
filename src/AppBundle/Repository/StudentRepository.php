<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Query;

/**
 * Class StudentRepository.
 *
 * @category Repository
 *
 * @author   Wils Iglesias <wiglesias83@gmail.com>
 */
class StudentRepository extends EntityRepository
{
    /**
     * @return QueryBuilder
     */
    public function getEnabledSortedByNameQB()
    {
        return $this->createQueryBuilder('s')
            ->where('s.enabled = :enabled')
            ->setParameter('enabled', true)
            ->orderBy('s.name', 'ASC')
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

    /**
     * @return QueryBuilder
     */
    public function getEnabledSortedBySurnameQB()
    {
        return $this->createQueryBuilder('s')
            ->where('s.enabled = :enabled')
            ->setParameter('enabled', true)
            ->orderBy('s.surname', 'ASC')
            ->addOrderBy('s.name', 'ASC')
        ;
    }

    /**
     * @return Query
     */
    public function getEnabledSortedBySurnameQ()
    {
        return $this->getEnabledSortedBySurnameQB()->getQuery();
    }

    /**
     * @return array
     */
    public function getEnabledSortedBySurname()
    {
        return $this->getEnabledSortedBySurnameQ()->getResult();
    }
}
