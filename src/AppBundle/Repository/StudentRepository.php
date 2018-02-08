<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Event;
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
     * @return QueryBuilder
     */
    public function getItemsStudentsByMonthAmountQB()
    {
        return $this->createQueryBuilder('s')
            ->join('s.events', 'e')
            ->andWhere('e.classroom = :classroom')
            ->setParameter('classroom', 3)
        ;
    }

    /**
     * @return Query
     */
    public function getItemsStudentsByMonthAmountQ()
    {
        return $this->getItemsStudentsByMonthAmountQB()->getQuery();
    }

    /**
     * @return array
     */
    public function getItemsStudentsByMonthAmount()
    {
        return $this->getItemsStudentsByMonthAmountQ()->getResult();
    }
}
