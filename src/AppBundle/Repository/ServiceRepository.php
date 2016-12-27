<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * Class ServiceRepository
 *
 * @category Repository
 * @package  AppBundle\Repository
 * @author   Anton Serra <aserratorta@gmail.com>
 */
class ServiceRepository extends EntityRepository
{
    public function findAllEnabledSortedByPosition()
    {
        $query = $this
            ->createQueryBuilder('s')
            ->where('s.enabled = :enabled')
            ->setParameter('enabled', true)
            ->orderBy('s.position', 'ASC');

        return $query->getQuery()->getResult();
    }
}
