<?php

namespace AppBundle\Listener;

use AncaRebeca\FullCalendarBundle\Event\CalendarEvent;
use AppBundle\Entity\Event as AppEvent;
use AppBundle\Repository\EventRepository;
use AppBundle\Service\EventTrasnformerFactoryService;


/**
 * Class FullCalendarListener.
 *
 * @category Listener
 *
 * @author   David Romaní  <david@flux.cat>
 */
class FullCalendarListener
{
    /**
     * @var EventRepository
     */
    private $ers;

    /**
     * @var EventTrasnformerFactoryService
     */
    private $etfs;

    /**
     * Methods.
     */

    /**
     * FullcalendarListener constructor.
     *
     * @param EventRepository $ers
     * @param EventTrasnformerFactoryService $etfs
     */
    public function __construct(EventRepository $ers, EventTrasnformerFactoryService $etfs)
    {
        $this->ers = $ers;
        $this->etfs = $etfs;
    }

    /**
     * @param CalendarEvent $calendarEvent
     */
    public function loadData(CalendarEvent $calendarEvent)
    {
        $startDate = $calendarEvent->getStart();
        $endDate = $calendarEvent->getEnd();

        $events = $this->ers->getFilteredByBeginAndEnd($startDate, $endDate);
        /** @var AppEvent $event */
        foreach ($events as $event) {
            $calendarEvent->addEvent($this->etfs->build($event));
        }
    }
}
