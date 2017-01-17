<?php

namespace AppBundle\Listener;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManager;
use Presta\SitemapBundle\Service\SitemapListenerInterface;
use Presta\SitemapBundle\Event\SitemapPopulateEvent;
use Presta\SitemapBundle\Sitemap\Url\UrlConcrete;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;

/**
 * Class SitemapListener
 *
 * @category Listener
 * @package  AppBundle\Listener
 * @author   Anton Serra <aserratorta@gmail.com>
 */
class SitemapListener implements SitemapListenerInterface
{
    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @var EntityManager
     */
    private $em;

    /**
     *
     *
     * Methods
     *
     *
     */

    /**
     * SitemapListener constructor
     *
     * @param RouterInterface $router
     * @param EntityManager $em
     */
    public function __construct(RouterInterface $router, EntityManager $em)
    {
        $this->router = $router;
        $this->em = $em;
    }

    /**
     * @param SitemapPopulateEvent $event
     */
    public function populateSitemap(SitemapPopulateEvent $event)
    {
        $section = $event->getSection();
        if (is_null($section) || $section == 'default') {
            // Homepage
            $url = $this->makeUrl('app_homepage');
            $event
                ->getUrlContainer()
                ->addUrl($this->makeUrlConcrete($url), 'default');
            // Services view
            $url = $this->makeUrl('app_services');
            $event
                ->getUrlContainer()
                ->addUrl($this->makeUrlConcrete($url, 1), 'default');
            // About us view
            $url = $this->makeUrl('app_aboutus');
            $event
                ->getUrlContainer()
                ->addUrl($this->makeUrlConcrete($url, 1), 'default');
            // Contact view
            $url = $this->makeUrl('app_contact');
            $event
                ->getUrlContainer()
                ->addUrl($this->makeUrlConcrete($url, 0.5), 'default');
            // Privacy Policy view
            $url = $this->makeUrl('app_privacy_policy');
            $event
                ->getUrlContainer()
                ->addUrl($this->makeUrlConcrete($url, 0.1), 'default');
            // Credits view
            $url = $this->makeUrl('app_credits');
            $event
                ->getUrlContainer()
                ->addUrl($this->makeUrlConcrete($url, 0.1), 'default');
        }
    }

    /**
     * @param string $routeName
     *
     * @return string
     */
    private function makeUrl($routeName)
    {
        return $this->router->generate(
            $routeName, array(), UrlGeneratorInterface::ABSOLUTE_URL
        );
    }

    /**
     * @param string         $url
     * @param int            $priority
     * @param \DateTime|null $date
     *
     * @return UrlConcrete
     */
    private function makeUrlConcrete($url, $priority = 1, $date = null)
    {
        return new UrlConcrete(
            $url,
            $date === null ? new \DateTime() : $date,
            UrlConcrete::CHANGEFREQ_WEEKLY,
            $priority
        );
    }
}
