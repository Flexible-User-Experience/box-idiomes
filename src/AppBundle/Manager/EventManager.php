<?php

namespace AppBundle\Manager;

use AppBundle\Entity\Event;

/**
 * Class EventManager.
 *
 * @category Manager
 */
class EventManager
{
    /**
     * @param Event $event
     *
     * @return Event|null
     */
    public function getFirstEventOf(Event $event)
    {
        $iteratedEvent = null;
        if (!is_null($event->getPrevious())) {
            $iteratedEvent = $event;
            while (!is_null($iteratedEvent->getPrevious())) {
                $iteratedEvent = $iteratedEvent->getPrevious();
            }
        }

        return $iteratedEvent;
    }

    /**
     * @param Event $event
     *
     * @return Event|null
     */
    public function getLastEventOf(Event $event)
    {
        $iteratedEvent = null;
        if (!is_null($event->getNext())) {
            $iteratedEvent = $event;
            while (!is_null($iteratedEvent->getNext())) {
                $iteratedEvent = $iteratedEvent->getNext();
            }
        }

        return $iteratedEvent;
    }

    /**
     * @param Event $event
     *
     * @return int
     */
    public function getRelatedEventsAmountOf(Event $event)
    {
        $amount = 0;
        $iteratedEvent = null;
        if (!is_null($event->getNext())) {
            $iteratedEvent = $event;
            while (!is_null($iteratedEvent->getNext())) {
                $iteratedEvent = $iteratedEvent->getNext();
                ++$amount;
            }
        }

        return $amount;
    }

    /**
     * @param Event $event
     *
     * @return int
     */
    public function getTotalRelatedEventsAmountOf(Event $event)
    {
        $amount = 0;
        $iteratedEvent = $this->getFirstEventOf($event);
        if (!is_null($event->getNext())) {
            while (!is_null($iteratedEvent->getNext())) {
                $iteratedEvent = $iteratedEvent->getNext();
                ++$amount;
            }
        }

        return $amount;
    }
}
