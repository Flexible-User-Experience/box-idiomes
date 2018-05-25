<?php

namespace AppBundle\Service;

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
     * Builder.
     *
     * @param AppEvent $appEvent
     *
     * @return EventFullCalendar
     */
    public function build(AppEvent $appEvent)
    {
        $eventFullCalendar = new EventFullCalendar($appEvent->getGroup()->getCode().' '.$appEvent->getGroup()->getBook(), $appEvent->getBegin());
        $eventFullCalendar->setEndDate($appEvent->getEnd());
        $eventFullCalendar->setBackgroundColor($appEvent->getGroup()->getColor());
        $eventFullCalendar->setTextColor('#FFFFFF');
        $eventFullCalendar->setColor($appEvent->getGroup()->getColor());
        $eventFullCalendar->setAllDay(false);
        $eventFullCalendar->setUrl($this->router->generate('admin_app_event_edit', array('id' => $appEvent->getId()), UrlGeneratorInterface::ABSOLUTE_PATH));

        return $eventFullCalendar;
    }
}
