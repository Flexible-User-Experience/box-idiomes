<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Student;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Query;

/**
 * Class BankRepository.
 *
 * @category Repository
 */
class BankRepository extends EntityRepository
{
    /**
     * @param Student|null $student
     *
     * @return QueryBuilder
     */
    public function getStudentRelatedItemsQB(Student $student = null)
    {
        $qb = $this->createQueryBuilder('b');

        if ($student instanceof Student && !is_null($student->getId())) {
            // $student is not null
            $qb
                ->where('b.parent = :parent')
                ->setParameter('parent', $student->getParent())
            ;
        } else {
            // $student is null
            $qb->where('b.id < 0');
        }

        return $qb;
    }

    /**
     * @param Student|null $student
     *
     * @return Query
     */
    public function getStudentRelatedItemsQ(Student $student = null)
    {
        return $this->getStudentRelatedItemsQB($student)->getQuery();
    }

    /**
     * @param Student|null $student
     *
     * @return array
     */
    public function getStudentRelatedItems(Student $student = null)
    {
        return $this->getStudentRelatedItemsQ($student)->getResult();
    }
}
