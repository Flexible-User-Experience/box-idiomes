<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Event;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Query;

/**
 * Class EventRepository.
 *
 * @category Repository
 *
 * @author   Wils Iglesias <wiglesias83@gmail.com>
 */
class EventRepository extends EntityRepository
{
    /**
     * @param Event $startEvent
     *
     * @return QueryBuilder
     */
    public function getRecordedEventsQB(Event $startEvent)
    {
        return $this->createQueryBuilder('e');
    }

    /**
     * @param Event $startEvent
     *
     * @return Query
     */
    public function getRecordedEventsQ(Event $startEvent)
    {
        return $this->getRecordedEventsQB($startEvent)->getQuery();
    }

    /**
     * @param Event $startEvent
     *
     * @return array
     */
    public function getRecordedEvents(Event $startEvent)
    {
        return $this->getRecordedEventsQ($startEvent)->getResult();
    }
}
