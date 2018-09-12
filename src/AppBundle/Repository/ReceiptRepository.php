<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Invoice;
use AppBundle\Entity\Receipt;
use AppBundle\Entity\Student;
use Doctrine\DBAL\Connection;
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
     * @param int $studentId
     * @param int $year
     * @param int $month
     *
     * @return QueryBuilder
     */
    public function findOnePreviousReceiptByStudentIdYearAndMonthOrNullQB($studentId, $year, $month)
    {
        $qb = $this
            ->createQueryBuilder('r')
            ->where('r.student = :student')
            ->andWhere('r.year = :year')
            ->andWhere('r.month = :month')
            ->setParameter('student', $studentId)
            ->setParameter('year', $year)
            ->setParameter('month', $month)
            ->setMaxResults(1)
        ;

        return $qb;
    }

    /**
     * @param int $studentId
     * @param int $year
     * @param int $month
     *
     * @return Query
     */
    public function findOnePreviousReceiptByStudentIdYearAndMonthOrNullQ($studentId, $year, $month)
    {
        return $this->findOnePreviousReceiptByStudentIdYearAndMonthOrNullQB($studentId, $year, $month)->getQuery();
    }

    /**
     * @param int $studentId
     * @param int $year
     * @param int $month
     *
     * @return Invoice|null
     *
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findOnePreviousReceiptByStudentIdYearAndMonthOrNull($studentId, $year, $month)
    {
        return $this->findOnePreviousReceiptByStudentIdYearAndMonthOrNullQ($studentId, $year, $month)->getOneOrNullResult();
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
     * @param int $studentId
     * @param int $year
     * @param int $month
     *
     * @return QueryBuilder
     */
    public function findOnePreviousGroupLessonsReceiptByStudentIdYearAndMonthOrNullQB($studentId, $year, $month)
    {
        $qb = $this
            ->findOnePreviousReceiptByStudentIdYearAndMonthOrNullQB($studentId, $year, $month)
            ->andWhere('r.isForPrivateLessons = :isForPrivateLessons')
            ->setParameter('isForPrivateLessons', false)
        ;

        return $qb;
    }

    /**
     * @param int $studentId
     * @param int $year
     * @param int $month
     *
     * @return Query
     */
    public function findOnePreviousGroupLessonsReceiptByStudentIdYearAndMonthOrNullQ($studentId, $year, $month)
    {
        return $this->findOnePreviousGroupLessonsReceiptByStudentIdYearAndMonthOrNullQB($studentId, $year, $month)->getQuery();
    }

    /**
     * @param int $studentId
     * @param int $year
     * @param int $month
     *
     * @return Invoice|null
     *
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findOnePreviousGroupLessonsReceiptByStudentIdYearAndMonthOrNull($studentId, $year, $month)
    {
        return $this->findOnePreviousGroupLessonsReceiptByStudentIdYearAndMonthOrNullQ($studentId, $year, $month)->getOneOrNullResult();
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
     * @param int $studentId
     * @param int $year
     * @param int $month
     *
     * @return QueryBuilder
     */
    public function findOnePreviousPrivateLessonsReceiptByStudentIdYearAndMonthOrNullQB($studentId, $year, $month)
    {
        $qb = $this
            ->findOnePreviousReceiptByStudentIdYearAndMonthOrNullQB($studentId, $year, $month)
            ->andWhere('r.isForPrivateLessons = :isForPrivateLessons')
            ->setParameter('isForPrivateLessons', true)
        ;

        return $qb;
    }

    /**
     * @param int $studentId
     * @param int $year
     * @param int $month
     *
     * @return Query
     */
    public function findOnePreviousPrivateLessonsReceiptByStudentIdYearAndMonthOrNullQ($studentId, $year, $month)
    {
        return $this->findOnePreviousPrivateLessonsReceiptByStudentIdYearAndMonthOrNullQB($studentId, $year, $month)->getQuery();
    }

    /**
     * @param int $studentId
     * @param int $year
     * @param int $month
     *
     * @return Invoice|null
     *
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findOnePreviousPrivateLessonsReceiptByStudentIdYearAndMonthOrNull($studentId, $year, $month)
    {
        return $this->findOnePreviousPrivateLessonsReceiptByStudentIdYearAndMonthOrNullQ($studentId, $year, $month)->getOneOrNullResult();
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

    /**
     * @param array $ids
     *
     * @return QueryBuilder
     */
    public function findByIdsArrayQB($ids)
    {
        $qb = $this
            ->createQueryBuilder('r')
            ->where('r.id IN (:ids)')
            ->setParameter('ids', $ids, Connection::PARAM_INT_ARRAY)
        ;

        return $qb;
    }

    /**
     * @param array $ids
     *
     * @return Query
     */
    public function findByIdsArrayQ($ids)
    {
        return $this->findByIdsArrayQB($ids)->getQuery();
    }

    /**
     * @param array $ids
     *
     * @return Receipt[]|null
     */
    public function findByIdsArray($ids)
    {
        return $this->findByIdsArrayQ($ids)->getResult();
    }
}
