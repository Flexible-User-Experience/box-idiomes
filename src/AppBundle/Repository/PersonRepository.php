<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Query;

/**
 * Class PersonRepository.
 *
 * @category Repository
 */
class PersonRepository extends EntityRepository
{
    /**
     * @return QueryBuilder
     */
    public function getEnabledSortedBySurnameQB()
    {
        return $this->createQueryBuilder('p')
            ->where('p.enabled = :enabled')
            ->setParameter('enabled', true)
            ->orderBy('p.surname', 'ASC')
            ->addOrderBy('p.name', 'ASC')
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
