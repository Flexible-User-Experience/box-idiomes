<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Query;

/**
 * Class TariffRepository.
 *
 * @category Repository
 *
 * @author   Wils Iglesias <wiglesias83@gmail.com>
 */
class TariffRepository extends EntityRepository
{
    /**
     * @return QueryBuilder
     */
    public function findAllSortedByYearAndPriceQB()
    {
        return $this->createQueryBuilder('t')
            ->orderBy('t.year', 'DESC')
            ->addOrderBy('t.price', 'ASC')
        ;
    }

    /**
     * @return Query
     */
    public function findAllSortedByYearAndPriceQ()
    {
        return $this->findAllSortedByYearAndPriceQB()->getQuery();
    }

    /**
     * @return array
     */
    public function findAllSortedByYearAndPrice()
    {
        return $this->findAllSortedByYearAndPriceQ()->getResult();
    }
}
