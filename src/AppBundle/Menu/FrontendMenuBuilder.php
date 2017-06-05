<?php

namespace AppBundle\Menu;

use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationChecker;

/**
 * Class FrontendMenuBuilder
 *
 * @category Menu
 * @package  AppBundle\Menu
 * @author   David Romaní <david@flux.cat>
 */
class FrontendMenuBuilder
{
    /**
     * @var FactoryInterface
     */
    private $factory;

    /**
     * @var AuthorizationChecker
     */
    private $ac;

    /**
     * @var TokenStorageInterface
     */
    private $ts;

    /**
     *
     *
     * Methods
     *
     *
     */

    /**
     * @param FactoryInterface     $factory
     * @param AuthorizationChecker $ac
     * @param TokenStorageInterface $ts
     */
    public function __construct(FactoryInterface $factory, AuthorizationChecker $ac, TokenStorageInterface $ts)
    {
        $this->factory = $factory;
        $this->ac = $ac;
        $this->ts      = $ts;
    }

    /**
     * @return ItemInterface
     */
    public function createTopMenu()
    {
        $menu = $this->factory->createItem('root');
        $menu->setChildrenAttribute('class', 'nav navbar-nav navbar-right');
        if ($this->ts->getToken() && $this->ac->isGranted('ROLE_CMS')) {
            $menu->addChild(
                'admin',
                array(
                    'label' => 'frontend.menu.cms',
                    'route' => 'sonata_admin_dashboard',
                )
            );
        }
        $menu->addChild(
            'app_services',
            array(
                'label'   => 'frontend.menu.services',
                'route'   => 'app_services',
            )
        );
        $menu->addChild(
            'app_academy',
            array(
                'label'   => 'frontend.menu.academy',
                'route'   => 'app_academy',
            )
        );
        $menu->addChild(
            'app_contact',
            array(
                'label'   => 'frontend.menu.contact',
                'route'   => 'app_contact',
            )
        );

        return $menu;
    }
}
