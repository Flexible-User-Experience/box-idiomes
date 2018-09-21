<?php

namespace AppBundle\Listener;

use AncaRebeca\FullCalendarBundle\Event\CalendarEvent;
use AppBundle\Entity\Event as AppEvent;
use AppBundle\Entity\TeacherAbsence;
use AppBundle\Repository\EventRepository;
use AppBundle\Repository\TeacherAbsenceRepository;
use AppBundle\Service\EventTrasnformerFactoryService;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;

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
     * @var RequestStack
     */
    private $rss;

    /**
     * @var Router
     */
    private $router;

    /**
     * Methods.
     */

    /**
     * FullcalendarListener constructor.
     *
     * @param EventRepository                $ers
     * @param TeacherAbsenceRepository       $tars
     * @param EventTrasnformerFactoryService $etfs
     * @param RequestStack                   $rss
     * @param RouterInterface                $router
     */
    public function __construct(EventRepository $ers, TeacherAbsenceRepository $tars, EventTrasnformerFactoryService $etfs, RequestStack $rss, RouterInterface $router)
    {
        $this->ers = $ers;
        $this->tars = $tars;
        $this->etfs = $etfs;
        $this->rss = $rss;
        $this->router = $router;
    }

    /**
     * @param CalendarEvent $calendarEvent
     */
    public function loadData(CalendarEvent $calendarEvent)
    {
        $startDate = $calendarEvent->getStart();
        $endDate = $calendarEvent->getEnd();

        // Admin dashboard action
        if ($this->rss->getCurrentRequest()->headers->get('referer') == $this->router->generate('sonata_admin_dashboard', array(), UrlGeneratorInterface::ABSOLUTE_URL)) {
            // Classroom events
            $events = $this->ers->getEnabledFilteredByBeginAndEnd($startDate, $endDate);
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
            // Admin student show action
        } else {
        }
    }
}
