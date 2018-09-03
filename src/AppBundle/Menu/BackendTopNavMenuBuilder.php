<?php

namespace AppBundle\Menu;

use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Class BackendTopNavMenuBuilder.
 *
 * @category Menu
 */
class BackendTopNavMenuBuilder
{
    /**
     * @var FactoryInterface
     */
    private $factory;

    /**
     * Methods.
     */

    /**
     * @param FactoryInterface $factory
     */
    public function __construct(FactoryInterface $factory)
    {
        $this->factory = $factory;
    }

    /**
     * @param RequestStack $requestStack
     *
     * @return ItemInterface
     */
    public function createTopNavMenu(RequestStack $requestStack)
    {
        $menu = $this->factory->createItem('topnav');
        $menu->setChildrenAttribute('class', 'nav navbar-nav navbar-right');
        $menu
            ->addChild(
                'homepage',
                array(
                    'label' => 'backend.admin.layout.top_nav_menu.homepage',
                    'route' => 'app_homepage',
                )
            )
            ->setExtras(
                array(
                    'icon' => '<i class="fa fa-globe"></i>',
                )
            )
        ;
        $menu
            ->addChild(
                'username',
                array(
                    'label' => 'backend.admin.layout.top_nav_menu.username',
                    'uri' => '#',
                )
            )
        ;
        $menu
            ->addChild(
                'logout',
                array(
                    'label' => 'backend.admin.layout.top_nav_menu.logout',
                    'route' => 'sonata_user_admin_security_logout',
                )
            )
        ;

        return $menu;
    }
}
