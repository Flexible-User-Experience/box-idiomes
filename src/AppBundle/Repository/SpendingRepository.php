<?php

namespace AppBundle\Repository;

use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\EntityRepository;

/**
 * Class SpendingRepository.
 *
 * @category Repository
 */
class SpendingRepository extends EntityRepository
{
    /**
     * @param \DateTime $date
     *
     * @return int
     *
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getMonthlyExpensesAmountForDate(\DateTime $date)
    {
        $begin = clone $date;
        $end = clone $date;
        $begin->modify('first day of this month');
        $end->modify('last day of this month');
        $query = $this->createQueryBuilder('i')
            ->select('SUM(i.baseAmount) as amount')
            ->where('i.date >= :begin')
            ->andWhere('i.date <= :end')
            ->setParameter('begin', $begin->format('Y-m-d'))
            ->setParameter('end', $end->format('Y-m-d'))
            ->getQuery()
        ;

        return is_null($query->getOneOrNullResult(AbstractQuery::HYDRATE_SINGLE_SCALAR)) ? 0 : floatval($query->getOneOrNullResult(AbstractQuery::HYDRATE_SINGLE_SCALAR));
    }
}
