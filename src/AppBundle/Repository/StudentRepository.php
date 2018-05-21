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
            ->addOrderBy('s.surname', 'ASC')
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

    /**
     * @param int $year
     * @param int $month
     *
     * @return QueryBuilder
     */
    public function getStudentsInEventsByYearAndMonthQB($year, $month)
    {
        return $this->createQueryBuilder('s')
            ->join('s.events', 'e')
            ->where('YEAR(e.begin) = :year')
            ->andWhere('MONTH(e.begin) = :month')
            ->setParameter('year', $year)
            ->setParameter('month', $month)
        ;
    }

    /**
     * @param int $year
     * @param int $month
     *
     * @return Query
     */
    public function getStudentsInEventsByYearAndMonthQ($year, $month)
    {
        return $this->getStudentsInEventsByYearAndMonthQB($year, $month)->getQuery();
    }

    /**
     * @param int $year
     * @param int $month
     *
     * @return array
     */
    public function getStudentsInEventsByYearAndMonth($year, $month)
    {
        return $this->getStudentsInEventsByYearAndMonthQ($year, $month)->getResult();
    }

    /**
     * @param int $year
     * @param int $month
     *
     * @return QueryBuilder
     */
    public function getStudentsInEventsByYearAndMonthSortedBySurnameQB($year, $month)
    {
        return $this->getStudentsInEventsByYearAndMonthQB($year, $month)
            ->orderBy('s.surname', 'ASC')
            ->addOrderBy('s.name', 'ASC')
        ;
    }

    /**
     * @param int $year
     * @param int $month
     *
     * @return Query
     */
    public function getStudentsInEventsByYearAndMonthSortedBySurnameQ($year, $month)
    {
        return $this->getStudentsInEventsByYearAndMonthSortedBySurnameQB($year, $month)->getQuery();
    }

    /**
     * @param int $year
     * @param int $month
     *
     * @return array
     */
    public function getStudentsInEventsByYearAndMonthSortedBySurname($year, $month)
    {
        return $this->getStudentsInEventsByYearAndMonthSortedBySurnameQ($year, $month)->getResult();
    }
}
