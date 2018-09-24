<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Student;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Query;

/**
 * Class EventRepository.
 *
 * @category Repository
 */
class EventRepository extends EntityRepository
{
    /**
     * @param \DateTime $startDate
     * @param \DateTime $endDate
     *
     * @return QueryBuilder
     */
    public function getFilteredByBeginAndEndQB(\DateTime $startDate, \DateTime $endDate)
    {
        return $this->createQueryBuilder('e')
            ->where('e.begin BETWEEN :startDate AND :endDate')
            ->setParameter('startDate', $startDate->format('Y-m-d H:i:s'))
            ->setParameter('endDate', $endDate->format('Y-m-d H:i:s'));
    }

    /**
     * @param \DateTime $startDate
     * @param \DateTime $endDate
     *
     * @return Query
     */
    public function getFilteredByBeginAndEndQ(\DateTime $startDate, \DateTime $endDate)
    {
        return $this->getFilteredByBeginAndEndQB($startDate, $endDate)->getQuery();
    }

    /**
     * @param \DateTime $startDate
     * @param \DateTime $endDate
     *
     * @return array
     */
    public function getFilteredByBeginAndEnd(\DateTime $startDate, \DateTime $endDate)
    {
        return $this->getFilteredByBeginAndEndQ($startDate, $endDate)->getResult();
    }

    /**
     * @param \DateTime $startDate
     * @param \DateTime $endDate
     *
     * @return QueryBuilder
     */
    public function getEnabledFilteredByBeginAndEndQB(\DateTime $startDate, \DateTime $endDate)
    {
        return $this->getFilteredByBeginAndEndQB($startDate, $endDate)
            ->andWhere('e.enabled = :enabled')
            ->setParameter('enabled', true);
    }

    /**
     * @param \DateTime $startDate
     * @param \DateTime $endDate
     *
     * @return Query
     */
    public function getEnabledFilteredByBeginAndEndQ(\DateTime $startDate, \DateTime $endDate)
    {
        return $this->getEnabledFilteredByBeginAndEndQB($startDate, $endDate)->getQuery();
    }

    /**
     * @param \DateTime $startDate
     * @param \DateTime $endDate
     *
     * @return array
     */
    public function getEnabledFilteredByBeginAndEnd(\DateTime $startDate, \DateTime $endDate)
    {
        return $this->getEnabledFilteredByBeginAndEndQ($startDate, $endDate)->getResult();
    }

    /**
     * @param \DateTime $startDate
     * @param \DateTime $endDate
     * @param Student   $student
     *
     * @return QueryBuilder
     */
    public function getEnabledFilteredByBeginEndAndStudentQB(\DateTime $startDate, \DateTime $endDate, Student $student)
    {
        return $this->getEnabledFilteredByBeginAndEndQB($startDate, $endDate)
            ->join('e.students', 's')
            ->andWhere('s.id = :sid')
            ->setParameter('sid', $student->getId())
        ;
    }

    /**
     * @param \DateTime $startDate
     * @param \DateTime $endDate
     * @param Student   $student
     *
     * @return Query
     */
    public function getEnabledFilteredByBeginEndAndStudentQ(\DateTime $startDate, \DateTime $endDate, Student $student)
    {
        return $this->getEnabledFilteredByBeginEndAndStudentQB($startDate, $endDate, $student)->getQuery();
    }

    /**
     * @param \DateTime $startDate
     * @param \DateTime $endDate
     * @param Student   $student
     *
     * @return array
     */
    public function getEnabledFilteredByBeginEndAndStudent(\DateTime $startDate, \DateTime $endDate, Student $student)
    {
        return $this->getEnabledFilteredByBeginEndAndStudentQ($startDate, $endDate, $student)->getResult();
    }

    /**
     * @param Student $student
     * @param int     $year
     * @param int     $month
     *
     * @return QueryBuilder
     */
    public function getPrivateLessonsAmountByStudentYearAndMonthQB(Student $student, $year, $month)
    {
        return $this->createQueryBuilder('e')
            ->join('e.students', 's')
            ->join('e.group', 'cg')
            ->where('YEAR(e.begin) = :year')
            ->andWhere('MONTH(e.begin) = :month')
            ->andWhere('s.id = :sid')
            ->andWhere('cg.isForPrivateLessons = :isForPrivateLessons')
            ->andWhere('e.enabled = :enabled')
            ->setParameter('sid', $student->getId())
            ->setParameter('year', $year)
            ->setParameter('month', $month)
            ->setParameter('isForPrivateLessons', true)
            ->setParameter('enabled', true);
    }

    /**
     * @param Student $student
     * @param int     $year
     * @param int     $month
     *
     * @return Query
     */
    public function getPrivateLessonsAmountByStudentYearAndMonthQ(Student $student, $year, $month)
    {
        return $this->getPrivateLessonsAmountByStudentYearAndMonthQB($student, $year, $month)->getQuery();
    }

    /**
     * @param Student $student
     * @param int     $year
     * @param int     $month
     *
     * @return float
     */
    public function getPrivateLessonsAmountByStudentYearAndMonth(Student $student, $year, $month)
    {
        return floatval(count($this->getPrivateLessonsAmountByStudentYearAndMonthQ($student, $year, $month)->getResult()));
    }
}
