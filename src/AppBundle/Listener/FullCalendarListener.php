<?php

namespace AppBundle\Listener;

use AncaRebeca\FullCalendarBundle\Event\CalendarEvent;
use AppBundle\Entity\Event as AppEvent;
use AppBundle\Entity\TeacherAbsence;
use AppBundle\Repository\EventRepository;
use AppBundle\Repository\TeacherAbsenceRepository;
use AppBundle\Service\EventTrasnformerFactoryService;

/**
 * Class FullCalendarListener.
 *
 * @category Listener
 */
class FullCalendarListener
{
    /**
     * @var EventRepository
     */
    private $ers;

    /**
     * @var TeacherAbsenceRepository
     */
    private $tars;

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
     * @param EventRepository                $ers
     * @param TeacherAbsenceRepository       $tars
     * @param EventTrasnformerFactoryService $etfs
     */
    public function __construct(EventRepository $ers, TeacherAbsenceRepository $tars, EventTrasnformerFactoryService $etfs)
    {
        $this->ers = $ers;
        $this->tars = $tars;
        $this->etfs = $etfs;
    }

    /**
     * @param CalendarEvent $calendarEvent
     */
    public function loadData(CalendarEvent $calendarEvent)
    {
        $startDate = $calendarEvent->getStart();
        $endDate = $calendarEvent->getEnd();

        // Classroom events
        $events = $this->ers->getFilteredByBeginAndEnd($startDate, $endDate);
        /** @var AppEvent $event */
        foreach ($events as $event) {
            $calendarEvent->addEvent($this->etfs->build($event));
        }

        // Teacher absences
        $events = $this->tars->getFilteredByBeginAndEnd($startDate, $endDate);
        /** @var TeacherAbsence $event */
        foreach ($events as $event) {
            $calendarEvent->addEvent($this->etfs->buildTeacherAbsence($event));
        }
    }
}
