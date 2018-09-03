<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * Class ContactMessageRepository.
 *
 * @category Repository
 */
class ContactMessageRepository extends EntityRepository
{
    /**
     * @return int
     */
    public function getNotReadMessagesAmount()
    {
        $qb = $this->createQueryBuilder('cm')
            ->where('cm.checked = :checked')
            ->setParameter('checked', false);

        return count($qb->getQuery()->getArrayResult());
    }
}
