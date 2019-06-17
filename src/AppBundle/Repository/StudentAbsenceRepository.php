<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Student;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Query;

/**
 * Class StudentAbsenceRepository.
 *
 * @category Repository
 */
class StudentAbsenceRepository extends EntityRepository
{
    /**
     * @param Student $student
     *
     * @return QueryBuilder
     */
    public function getStudentAbsencesSortedByDateQB(Student $student)
    {
        return $this->createQueryBuilder('sa')
            ->where('sa.student = :student')
            ->setParameter('student', $student)
            ->orderBy('sa.day', 'DESC')
            ->addOrderBy('sa.type', 'ASC');
    }

    /**
     * @param Student $student
     *
     * @return Query
     */
    public function getStudentAbsencesSortedByDateQ(Student $student)
    {
        return $this->getStudentAbsencesSortedByDateQB($student)->getQuery();
    }

    /**
     * @param Student $student
     *
     * @return array
     */
    public function getStudentAbsencesSortedByDate(Student $student)
    {
        return $this->getStudentAbsencesSortedByDateQ($student)->getResult();
    }

    /**
     * @param \DateTime $startDate
     * @param \DateTime $endDate
     *
     * @return QueryBuilder
     */
    public function getFilteredByBeginAndEndQB(\DateTime $startDate, \DateTime $endDate)
    {
        return $this->createQueryBuilder('sa')
            ->where('sa.day BETWEEN :startDate AND :endDate')
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
}
