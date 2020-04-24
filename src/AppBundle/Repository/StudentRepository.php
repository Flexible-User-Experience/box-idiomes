<?php

namespace AppBundle\Repository;

use AppBundle\Entity\ClassGroup;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Query;

/**
 * Class StudentRepository.
 *
 * @category Repository
 */
class StudentRepository extends EntityRepository
{
    /**
     * @return QueryBuilder
     */
    public function getEnabledSortedByNameQB()
    {
        return $this->getAllSortedByNameQB()
            ->where('s.enabled = :enabled')
            ->setParameter('enabled', true)
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
    public function getAllSortedByNameQB()
    {
        return $this->createQueryBuilder('s')
            ->orderBy('s.name', 'ASC')
            ->addOrderBy('s.surname', 'ASC')
        ;
    }

    /**
     * @return Query
     */
    public function getAllSortedByNameQ()
    {
        return $this->getAllSortedByNameQB()->getQuery();
    }

    /**
     * @return array
     */
    public function getAllSortedByName()
    {
        return $this->getAllSortedByNameQ()->getResult();
    }

    /**
     * @return QueryBuilder
     */
    public function getEnabledSortedBySurnameQB()
    {
        return $this->getAllSortedBySurnameQB()
            ->where('s.enabled = :enabled')
            ->setParameter('enabled', true)
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
     * @return QueryBuilder
     */
    public function getAllSortedBySurnameQB()
    {
        return $this->createQueryBuilder('s')
            ->orderBy('s.surname', 'ASC')
            ->addOrderBy('s.name', 'ASC')
        ;
    }

    /**
     * @return Query
     */
    public function getAllSortedBySurnameQ()
    {
        return $this->getAllSortedBySurnameQB()->getQuery();
    }

    /**
     * @return array
     */
    public function getAllSortedBySurname()
    {
        return $this->getAllSortedBySurnameQ()->getResult();
    }

    /**
     * @return QueryBuilder
     */
    public function getEnabledSortedBySurnameValidTariffQB()
    {
        return $this->getEnabledSortedBySurnameQB()->andWhere('s.tariff IS NOT NULL');
    }

    /**
     * @return Query
     */
    public function getEnabledSortedBySurnameValidTariffQ()
    {
        return $this->getEnabledSortedBySurnameValidTariffQB()->getQuery();
    }

    /**
     * @return array
     */
    public function getEnabledSortedBySurnameWithValidTariff()
    {
        return $this->getEnabledSortedBySurnameValidTariffQ()->getResult();
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
            ->join('e.group', 'cg')
            ->where('YEAR(e.begin) = :year')
            ->andWhere('MONTH(e.begin) = :month')
            ->andWhere('e.enabled = :enabled')
            ->setParameter('year', $year)
            ->setParameter('month', $month)
            ->setParameter('enabled', true)
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

    /**
     * @param int $year
     * @param int $month
     *
     * @return QueryBuilder
     */
    public function getStudentsInEventsForYearAndMonthSortedBySurnameWithValidTariffQB($year, $month)
    {
        return $this->getStudentsInEventsByYearAndMonthSortedBySurnameQB($year, $month)
            ->andWhere('s.tariff IS NOT NULL')
            ->andWhere('s.isPaymentExempt = :exempt')
            ->setParameter('exempt', false)
        ;
    }

    /**
     * @param int $year
     * @param int $month
     *
     * @return Query
     */
    public function getStudentsInEventsForYearAndMonthSortedBySurnameWithValidTariffQ($year, $month)
    {
        return $this->getStudentsInEventsForYearAndMonthSortedBySurnameWithValidTariffQB($year, $month)->getQuery();
    }

    /**
     * @param int $year
     * @param int $month
     *
     * @return array
     */
    public function getStudentsInEventsForYearAndMonthSortedBySurnameWithValidTariff($year, $month)
    {
        return $this->getStudentsInEventsForYearAndMonthSortedBySurnameWithValidTariffQ($year, $month)->getResult();
    }

    /**
     * @param int $year
     * @param int $month
     *
     * @return QueryBuilder
     */
    public function getPrivateLessonStudentsInEventsForYearAndMonthSortedBySurnameWithValidTariffQB($year, $month)
    {
        return $this->getStudentsInEventsForYearAndMonthSortedBySurnameWithValidTariffQB($year, $month)
            ->andWhere('cg.isForPrivateLessons = :isForPrivateLessons')
            ->setParameter('isForPrivateLessons', true)
        ;
    }

    /**
     * @param int $year
     * @param int $month
     *
     * @return Query
     */
    public function getPrivateLessonStudentsInEventsForYearAndMonthSortedBySurnameWithValidTariffQ($year, $month)
    {
        return $this->getPrivateLessonStudentsInEventsForYearAndMonthSortedBySurnameWithValidTariffQB($year, $month)->getQuery();
    }

    /**
     * @param int $year
     * @param int $month
     *
     * @return array
     */
    public function getPrivateLessonStudentsInEventsForYearAndMonthSortedBySurnameWithValidTariff($year, $month)
    {
        return $this->getPrivateLessonStudentsInEventsForYearAndMonthSortedBySurnameWithValidTariffQ($year, $month)->getResult();
    }

    /**
     * @param int $year
     * @param int $month
     *
     * @return QueryBuilder
     */
    public function getGroupLessonStudentsInEventsForYearAndMonthSortedBySurnameWithValidTariffQB($year, $month)
    {
        return $this->getStudentsInEventsForYearAndMonthSortedBySurnameWithValidTariffQB($year, $month)
            ->andWhere('cg.isForPrivateLessons = :isForPrivateLessons')
            ->setParameter('isForPrivateLessons', false)
        ;
    }

    /**
     * @param int $year
     * @param int $month
     *
     * @return Query
     */
    public function getGroupLessonStudentsInEventsForYearAndMonthSortedBySurnameWithValidTariffQ($year, $month)
    {
        return $this->getGroupLessonStudentsInEventsForYearAndMonthSortedBySurnameWithValidTariffQB($year, $month)->getQuery();
    }

    /**
     * @param int $year
     * @param int $month
     *
     * @return array
     */
    public function getGroupLessonStudentsInEventsForYearAndMonthSortedBySurnameWithValidTariff($year, $month)
    {
        return $this->getGroupLessonStudentsInEventsForYearAndMonthSortedBySurnameWithValidTariffQ($year, $month)->getResult();
    }

    /**
     * @param ClassGroup $classGroup
     *
     * @return QueryBuilder
     */
    public function getStudentsInClassGroupQB(ClassGroup $classGroup)
    {
        return $this->createQueryBuilder('s');
    }

    /**
     * @param ClassGroup $classGroup
     *
     * @return Query
     */
    public function getStudentsInClassGroupQ(ClassGroup $classGroup)
    {
        return $this->getStudentsInClassGroupQB($classGroup)->getQuery();
    }

    /**
     * @param ClassGroup $classGroup
     *
     * @return array
     */
    public function getStudentsInClassGroup(ClassGroup $classGroup)
    {
        return $this->getStudentsInClassGroupQ($classGroup)->getResult();
    }
}
