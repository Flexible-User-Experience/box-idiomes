<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * Class TeacherRepository
 *
 * @category Repository
 * @package  AppBundle\Repository
 * @author   Anton Serra <aserratorta@gmail.com>
 */
class TeacherRepository extends EntityRepository
{
    public function findAllEnabledSortedByPosition()
    {
        $query = $this
            ->createQueryBuilder('t')
            ->where('t.enabled = :enabled')
            ->setParameter('enabled', true)
            ->orderBy('t.position', 'ASC');

        return $query->getQuery()->getResult();
    }
}
