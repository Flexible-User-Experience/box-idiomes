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
//        $startDate = $this->parseDateTime($calendarEvent->getRequest()->get('start'));      //getStartDatetime();
        // $range_start = parseDateTime($_GET['start']);
//        $endDate = $this->parseDateTime($calendarEvent->getRequest()->get('end')); //$calendarEvent->getEndDatetime();

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
            $eventEntity->setFgColor('#000000');
            $eventEntity->setUrl($this->router->generate('admin_app_event_edit', array('id' => $event->getId()), UrlGeneratorInterface::ABSOLUTE_PATH));
//            $eventEntity->setCssClass('my-custom-class'); // a custom class you may want to apply to event labels

            //finally, add the event to the CalendarEvent for displaying on the calendar
            $calendarEvent->addEvent($eventEntity);
        }
    }

    /**
     * @param string $string
     * @param null   $timezone
     *
     * @return \DateTime
     */
    private function parseDateTime($string, $timezone = null)
    {
        $date = new \DateTime(
            $string,
            $timezone ? $timezone : new \DateTimeZone('UTC')
        // Used only when the string is ambiguous.
        // Ignored if string has a timezone offset in it.
        );
        if ($timezone) {
            // If our timezone was ignored above, force it.
            $date->setTimezone($timezone);
        }

        return $date;
    }

    /**
     * @param $datetime
     *
     * @return \DateTime
     */
    private function stripTime($datetime)
    {
        return new \DateTime($datetime->format('Y-m-d'));
    }
}
