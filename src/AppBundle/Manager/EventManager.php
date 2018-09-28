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
        if (!is_null($event->getPrevious())) {
            $iteratedEvent = $this->getFirstEventOf($event);
        } else {
            $iteratedEvent = $event;
        }
        if (!is_null($event->getNext())) {
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
     * @return array
     */
    public function getProgressBarPercentilesOf(Event $event)
    {
        $progressBarPercentiles = array();
        $total = $this->getTotalRelatedEventsAmountOf($event);
        $involved = $this->getRelatedEventsAmountOf($event);
        if (0 != $total) {
            $progressBarPercentiles['last'] = round(($involved * 55) / $total, 0) + 15;
            $progressBarPercentiles['first'] = 85 - $progressBarPercentiles['last'];
        } else {
            $progressBarPercentiles['last'] = 15;
            $progressBarPercentiles['first'] = 85;
        }

        return $progressBarPercentiles;
    }

    /**
     * @param Event $event
     *
     * @return array
     */
    public function getRangeChoices(Event $event)
    {
        $result = array();
        if (!is_null($event->getNext())) {
            $iteratedEvent = $event;
            while (!is_null($iteratedEvent->getNext())) {
                $iteratedEvent = $iteratedEvent->getNext();
                $result[$iteratedEvent->getId()] = $iteratedEvent->getBegin()->format('d/m/Y H:i');
            }
        }

        return $result;
    }

    /**
     * @param Event $event
     *
     * @return array
     */
    public function getInclusiveRangeChoices(Event $event)
    {
        $result = array();
        $result[$event->getId()] = $event->getBegin()->format('d/m/Y H:i');
        if (!is_null($event->getNext())) {
            $iteratedEvent = $event;
            while (!is_null($iteratedEvent->getNext())) {
                $iteratedEvent = $iteratedEvent->getNext();
                $result[$iteratedEvent->getId()] = $iteratedEvent->getBegin()->format('d/m/Y H:i');
            }
        }

        return $result;
    }

    /**
     * @param Event[]|array $events
     *
     * @return bool
     */
    public function decidePrivateLessonsTariff($events)
    {
        $isPrivateLesson = false;
        /** @var Event $event */
        foreach ($events as $event) {
            if (count($event->getStudents()) > 1) {
                $isPrivateLesson = true;
            }
        }

        return $isPrivateLesson;
    }
}
