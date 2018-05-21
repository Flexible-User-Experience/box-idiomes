<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Teacher;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Query;

/**
 * Class TeacherAbsenceRepository.
 *
 * @category Repository
 *
 * @author   Wils Iglesias <wiglesias83@gmail.com>
 */
class TeacherAbsenceRepository extends EntityRepository
{
    /**
     * @param Teacher $teacher
     *
     * @return QueryBuilder
     */
    public function getTeacherAbsencesSortedByDateQB(Teacher $teacher)
    {
        return $this->createQueryBuilder('ta')
            ->where('ta.teacher = :teacher')
            ->setParameter('teacher', $teacher)
            ->orderBy('ta.day', 'DESC')
            ->addOrderBy('ta.type', 'ASC')
            ;
    }

    /**
     * @param Teacher $teacher
     *
     * @return Query
     */
    public function getTeacherAbsencesSortedByDateQ(Teacher $teacher)
    {
        return $this->getTeacherAbsencesSortedByDateQB($teacher)->getQuery();
    }

    /**
     * @param Teacher $teacher
     *
     * @return array
     */
    public function getTeacherAbsencesSortedByDate(Teacher $teacher)
    {
        return $this->getTeacherAbsencesSortedByDateQ($teacher)->getResult();
    }
}
