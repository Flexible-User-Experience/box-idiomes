<?php

namespace AppBundle\Listener;

use ADesigns\CalendarBundle\Event\CalendarEvent;
use ADesigns\CalendarBundle\Entity\EventEntity;
use AppBundle\Entity\Event;
use AppBundle\Repository\EventRepository;

/**
 * Class EventListener.
 *
 * @category Listener
 *
 * @author   Wils Iglesias <wiglesias83@gmail.com>
 */
class EventListener
{
    /**
     * @var EventRepository
     */
    private $ers;

    /**
     * EventListener constructor.
     *
     * @param EventRepository $ers
     */
    public function __construct(EventRepository $ers)
    {
        $this->ers = $ers;
    }

    public function loadEvents(CalendarEvent $calendarEvent)
    {
        $startDate = $calendarEvent->getStartDatetime();
        $endDate = $calendarEvent->getEndDatetime();

        // The original request so you can get filters from the calendar
        // Use the filter in your query for example
//        $request = $calendarEvent->getRequest();
//        $filter = $request->get('filter');

        $events = $this->ers->getFilteredByBeginAndEnd($startDate, $endDate);

        // $companyEvents and $companyEvent in this example
        // represent entities from your database, NOT instances of EventEntity
        // within this bundle.
        //
        // Create EventEntity instances and populate it's properties with data
        // from your own entities/database values.

        /** @var Event $event */
        foreach ($events as $event) {
            // create an event with a start/end time, or an all day event
            $eventEntity = new EventEntity($event->getGroup()->getCode().' '.$event->getGroup()->getBook(), $event->getBegin(), $event->getEnd());

            //optional calendar event settings
            $eventEntity->setBgColor($event->getGroup()->getColor());
            $eventEntity->setFgColor('#FFFFFF');
//            $eventEntity->setCssClass('my-custom-class'); // a custom class you may want to apply to event labels

            //finally, add the event to the CalendarEvent for displaying on the calendar
            $calendarEvent->addEvent($eventEntity);
        }
    }
}
