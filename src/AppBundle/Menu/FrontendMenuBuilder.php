<?php

namespace AppBundle\Menu;

use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;
use Symfony\Component\HttpFoundation\RequestStack;
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
     *
     *
     * Methods
     *
     *
     */

    /**
     * @param FactoryInterface     $factory
     * @param AuthorizationChecker $ac
     */
    public function __construct(FactoryInterface $factory, AuthorizationChecker $ac)
    {
        $this->factory = $factory;
        $this->ac = $ac;
    }

    /**
     * @param RequestStack $requestStack
     *
     * @return ItemInterface
     */
    public function createTopMenu(RequestStack $requestStack)
    {
//        $route = $requestStack->getCurrentRequest()->get('_route');
        $menu = $this->factory->createItem('root');
        $menu->setChildrenAttribute('class', 'nav navbar-nav navbar-right');
        if ($this->ac->isGranted('ROLE_CMS')) {
            $menu->addChild(
                'admin',
                array(
                    'label' => 'frontend.menu.cms',
                    'route' => 'sonata_admin_dashboard',
                )
            );
        }
//        $menu->addChild(
//            'app_teachers',
//            array(
//                'label' => 'frontend.menu.teachers',
//                'route' => 'app_teachers',
//            )
//        );
        $menu->addChild(
            'app_services',
            array(
                'label'   => 'frontend.menu.services',
                'route'   => 'app_services',
            )
        );
        $menu->addChild(
            'app_aboutus',
            array(
                'label'   => 'frontend.menu.aboutus',
                'route'   => 'app_aboutus',
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
