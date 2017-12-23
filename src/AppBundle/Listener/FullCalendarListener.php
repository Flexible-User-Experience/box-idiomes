<?php

namespace AppBundle\Listener;

use AncaRebeca\FullCalendarBundle\Event\CalendarEvent;
use AncaRebeca\FullCalendarBundle\Model\Event;
use AppBundle\Entity\Event as AppEvent;
use AppBundle\Repository\EventRepository;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

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
     * @var RouterInterface
     */
    private $router;

    /**
     * Methods
     */

    /**
     * FullcalendarListener constructor.
     *
     * @param EventRepository $ers
     * @param RouterInterface $router
     */
    public function __construct(EventRepository $ers, RouterInterface $router)
    {
        $this->ers = $ers;
        $this->router = $router;
    }

    /**
     * @param CalendarEvent $calendarEvent
     *
     * @return void
     */
    public function loadData(CalendarEvent $calendarEvent)
    {
        $startDate = $calendarEvent->getStart();
        $endDate = $calendarEvent->getEnd();

        $events = $this->ers->getFilteredByBeginAndEnd($startDate, $endDate);
        /** @var AppEvent $event */
        foreach ($events as $event) {
            // create an event with a start/end time, or an all day event
            $eventEntity = new Event($event->getGroup()->getCode().' '.$event->getGroup()->getBook(), $event->getBegin());
            //optional calendar event settings
            $eventEntity->setBackgroundColor($event->getGroup()->getColor());
            $eventEntity->setColor('#000000');
            $eventEntity->setUrl($this->router->generate('admin_app_event_edit', array('id' => $event->getId()), UrlGeneratorInterface::ABSOLUTE_PATH));
            $eventEntity->setAllDay(false);
            //finally, add the event to the CalendarEvent for displaying on the calendar
            $calendarEvent->addEvent($eventEntity);
        }
    }
}
