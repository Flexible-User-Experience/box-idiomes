<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Event;
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
            ->setParameter('endDate', $endDate->format('Y-m-d H:i:s'))
        ;
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
            ->setParameter('sid', $student->getId())
            ->setParameter('year', $year)
            ->setParameter('month', $month)
            ->setParameter('isForPrivateLessons', true)
        ;
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

    /**
     * @param Event $event
     *
     * @return Event|null
     */
    public function getFirstEventOf(Event $event)
    {
        $iteratedEvent = null;
        if (!is_null($event->getPrevious())) {
            $iteratedEvent = $event;
            while (!is_null($iteratedEvent->getPrevious())) {
                $iteratedEvent = $iteratedEvent->getPrevious();
            }
        }

        return $iteratedEvent;
    }

    /**
     * @param Event $event
     *
     * @return Event|null
     */
    public function getLastEventOf(Event $event)
    {
        $iteratedEvent = null;
        if (!is_null($event->getNext())) {
            $iteratedEvent = $event;
            while (!is_null($iteratedEvent->getNext())) {
                $iteratedEvent = $iteratedEvent->getNext();
            }
        }

        return $iteratedEvent;
    }
}
