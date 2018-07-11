<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Invoice;
use AppBundle\Entity\Receipt;
use AppBundle\Entity\Student;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;

/**
 * Class ReceiptRepository.
 *
 * @category Repository
 */
class ReceiptRepository extends EntityRepository
{
    /**
     * @param Student $student
     * @param int     $year
     * @param int     $month
     *
     * @return QueryBuilder
     */
    public function findOnePreviousReceiptByStudentYearAndMonthOrNullQB(Student $student, $year, $month)
    {
        $qb = $this
            ->createQueryBuilder('r')
            ->where('r.student = :student')
            ->andWhere('r.year = :year')
            ->andWhere('r.month = :month')
            ->setParameter('student', $student)
            ->setParameter('year', $year)
            ->setParameter('month', $month)
            ->setMaxResults(1)
        ;

        return $qb;
    }

    /**
     * @param Student $student
     * @param int     $year
     * @param int     $month
     *
     * @return Query
     */
    public function findOnePreviousReceiptByStudentYearAndMonthOrNullQ(Student $student, $year, $month)
    {
        return $this->findOnePreviousReceiptByStudentYearAndMonthOrNullQB($student, $year, $month)->getQuery();
    }

    /**
     * @param Student $student
     * @param int     $year
     * @param int     $month
     *
     * @return Invoice|null
     *
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findOnePreviousReceiptByStudentYearAndMonthOrNull(Student $student, $year, $month)
    {
        return $this->findOnePreviousReceiptByStudentYearAndMonthOrNullQ($student, $year, $month)->getOneOrNullResult();
    }

    /**
     * @param Student $student
     * @param int     $year
     * @param int     $month
     *
     * @return QueryBuilder
     */
    public function findOnePreviousGroupLessonsReceiptByStudentYearAndMonthOrNullQB(Student $student, $year, $month)
    {
        $qb = $this
            ->findOnePreviousReceiptByStudentYearAndMonthOrNullQB($student, $year, $month)
            ->andWhere('r.isForPrivateLessons = :isForPrivateLessons')
            ->setParameter('isForPrivateLessons', false)
        ;

        return $qb;
    }

    /**
     * @param Student $student
     * @param int     $year
     * @param int     $month
     *
     * @return Query
     */
    public function findOnePreviousGroupLessonsReceiptByStudentYearAndMonthOrNullQ(Student $student, $year, $month)
    {
        return $this->findOnePreviousGroupLessonsReceiptByStudentYearAndMonthOrNullQB($student, $year, $month)->getQuery();
    }

    /**
     * @param Student $student
     * @param int     $year
     * @param int     $month
     *
     * @return Invoice|null
     *
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findOnePreviousGroupLessonsReceiptByStudentYearAndMonthOrNull(Student $student, $year, $month)
    {
        return $this->findOnePreviousGroupLessonsReceiptByStudentYearAndMonthOrNullQ($student, $year, $month)->getOneOrNullResult();
    }

    /**
     * @param Student $student
     * @param int     $year
     * @param int     $month
     *
     * @return QueryBuilder
     */
    public function findOnePreviousPrivateLessonsReceiptByStudentYearAndMonthOrNullQB(Student $student, $year, $month)
    {
        $qb = $this
            ->findOnePreviousReceiptByStudentYearAndMonthOrNullQB($student, $year, $month)
            ->andWhere('r.isForPrivateLessons = :isForPrivateLessons')
            ->setParameter('isForPrivateLessons', true)
        ;

        return $qb;
    }

    /**
     * @param Student $student
     * @param int     $year
     * @param int     $month
     *
     * @return Query
     */
    public function findOnePreviousPrivateLessonsReceiptByStudentYearAndMonthOrNullQ(Student $student, $year, $month)
    {
        return $this->findOnePreviousPrivateLessonsReceiptByStudentYearAndMonthOrNullQB($student, $year, $month)->getQuery();
    }

    /**
     * @param Student $student
     * @param int     $year
     * @param int     $month
     *
     * @return Invoice|null
     *
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findOnePreviousPrivateLessonsReceiptByStudentYearAndMonthOrNull(Student $student, $year, $month)
    {
        return $this->findOnePreviousPrivateLessonsReceiptByStudentYearAndMonthOrNullQ($student, $year, $month)->getOneOrNullResult();
    }

    /**
     * @return QueryBuilder
     */
    public function getAllSortedByNumberDescQB()
    {
        $qb = $this
            ->createQueryBuilder('r')
            ->orderBy('r.id', 'DESC')
        ;

        return $qb;
    }

    /**
     * @return Query
     */
    public function getAllSortedByNumberDescQ()
    {
        return $this->getAllSortedByNumberDescQB()->getQuery();
    }

    /**
     * @return Receipt[]|null
     */
    public function getAllSortedByNumberDesc()
    {
        return $this->getAllSortedByNumberDescQ()->getResult();
    }
}
