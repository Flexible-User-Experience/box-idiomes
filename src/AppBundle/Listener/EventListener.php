<?php

namespace AppBundle\Listener;

use ADesigns\CalendarBundle\Event\CalendarEvent;
use ADesigns\CalendarBundle\Entity\EventEntity;
use AppBundle\Entity\Event;
use AppBundle\Repository\EventRepository;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * Class EventListener.
 *
 * @category Listener
 *
 * @author   Wils Iglesias <wiglesias83@gmail.com>
 * @author   David Romaní  <david@flux.cat>
 */
class EventListener
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
     * EventListener constructor.
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
     */
    public function loadEvents(CalendarEvent $calendarEvent)
    {
        $startDate = $calendarEvent->getStartDatetime();
        $endDate = $calendarEvent->getEndDatetime();
        $events = $this->ers->getFilteredByBeginAndEnd($startDate, $endDate);

        /** @var Event $event */
        foreach ($events as $event) {
            // create an event with a start/end time, or an all day event
            $eventEntity = new EventEntity($event->getGroup()->getCode().' '.$event->getGroup()->getBook(), $event->getBegin(), $event->getEnd());

            //optional calendar event settings
            $eventEntity->setBgColor($event->getGroup()->getColor());
            $eventEntity->setFgColor('#000000');
            $eventEntity->setUrl($this->router->generate('admin_app_event_edit', array('id' => $event->getId()), UrlGeneratorInterface::ABSOLUTE_PATH));

            //finally, add the event to the CalendarEvent for displaying on the calendar
            $calendarEvent->addEvent($eventEntity);
        }
    }
}
