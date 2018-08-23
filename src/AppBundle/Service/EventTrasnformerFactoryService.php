<?php

namespace AppBundle\Service;

use AppBundle\Entity\TeacherAbsence;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;
use AppBundle\Entity\Event as AppEvent;
use AppBundle\Entity\EventFullCalendar;

/**
 * Class EventTrasnformerFactoryService.
 *
 * @category Service
 */
class EventTrasnformerFactoryService
{
    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * Methods.
     */

    /**
     * EventTrasnformerFactoryService constructor.
     *
     * @param RouterInterface $router
     */
    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    /**
     * Classroom event builder.
     *
     * @param AppEvent $appEvent
     *
     * @return EventFullCalendar
     */
    public function build(AppEvent $appEvent)
    {
        $eventFullCalendar = new EventFullCalendar($appEvent->getCalendarTitle(), $appEvent->getBegin());
        $eventFullCalendar->setEndDate($appEvent->getEnd());
        $eventFullCalendar->setBackgroundColor($appEvent->getGroup()->getColor());
        $eventFullCalendar->setTextColor('#FFFFFF');
        $eventFullCalendar->setColor($appEvent->getGroup()->getColor());
        $eventFullCalendar->setAllDay(false);
        $eventFullCalendar->setUrl($this->router->generate('admin_app_event_edit', array('id' => $appEvent->getId()), UrlGeneratorInterface::ABSOLUTE_PATH));

        return $eventFullCalendar;
    }

    /**
     * Teacher absence builder.
     *
     * @param TeacherAbsence $teacherAbsence
     *
     * @return EventFullCalendar
     */
    public function buildTeacherAbsence(TeacherAbsence $teacherAbsence)
    {
        $eventFullCalendar = new EventFullCalendar($teacherAbsence->getCalendarTitle(), $teacherAbsence->getDay());
        $eventFullCalendar->setEndDate($teacherAbsence->getDay());
        $eventFullCalendar->setBackgroundColor('#FA141B');
        $eventFullCalendar->setTextColor('#FFFFFF');
        $eventFullCalendar->setColor('#FA141B');
        $eventFullCalendar->setAllDay(true);
        $eventFullCalendar->setUrl($this->router->generate('admin_app_teacherabsence_edit', array('id' => $teacherAbsence->getId()), UrlGeneratorInterface::ABSOLUTE_PATH));

        return $eventFullCalendar;
    }
}
