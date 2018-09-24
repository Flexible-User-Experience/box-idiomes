<?php

namespace AppBundle\Listener;

use AncaRebeca\FullCalendarBundle\Event\CalendarEvent;
use AppBundle\Entity\Event as AppEvent;
use AppBundle\Entity\Student;
use AppBundle\Entity\TeacherAbsence;
use AppBundle\Repository\EventRepository;
use AppBundle\Repository\StudentRepository;
use AppBundle\Repository\TeacherAbsenceRepository;
use AppBundle\Service\EventTrasnformerFactoryService;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\HttpFoundation\RequestStack;
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
     * @var StudentRepository
     */
    private $srs;

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
     * @param StudentRepository              $srs
     * @param EventTrasnformerFactoryService $etfs
     * @param RequestStack                   $rss
     * @param RouterInterface                $router
     */
    public function __construct(EventRepository $ers, TeacherAbsenceRepository $tars, StudentRepository $srs, EventTrasnformerFactoryService $etfs, RequestStack $rss, RouterInterface $router)
    {
        $this->ers = $ers;
        $this->tars = $tars;
        $this->srs = $srs;
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

        $referer = $this->rss->getCurrentRequest()->headers->get('referer');

        if ($this->rss->getCurrentRequest()->getBaseUrl()) {
            // probably dev environment
            $path = substr($referer, strpos($referer, $this->rss->getCurrentRequest()->getBaseUrl()));
            $path = str_replace($this->rss->getCurrentRequest()->getBaseUrl(), '', $path);
        } else {
            // prod environment
            $path = str_replace($this->rss->getCurrentRequest()->getSchemeAndHttpHost(), '', $referer);
        }

        $matcher = $this->router->getMatcher();
        $parameters = $matcher->match($path);
        $route = $parameters['_route'];

        if ('sonata_admin_dashboard' == $route) {
            //// admin dashboard action
            // classroom events
            $events = $this->ers->getEnabledFilteredByBeginAndEnd($startDate, $endDate);
            /** @var AppEvent $event */
            foreach ($events as $event) {
                $calendarEvent->addEvent($this->etfs->build($event));
            }
            // teacher absences
            $events = $this->tars->getFilteredByBeginAndEnd($startDate, $endDate);
            /** @var TeacherAbsence $event */
            foreach ($events as $event) {
                $calendarEvent->addEvent($this->etfs->buildTeacherAbsence($event));
            }
        } elseif ('admin_app_student_show' == $route) {
            //// admin student show action
            // student events
            /** @var Student $student */
            $student = $this->srs->find(intval($parameters['id']));
            $events = $this->ers->getEnabledFilteredByBeginEndAndStudent($startDate, $endDate, $student);
            /** @var AppEvent $event */
            foreach ($events as $event) {
                $calendarEvent->addEvent($this->etfs->build($event));
            }
        }
    }
}
