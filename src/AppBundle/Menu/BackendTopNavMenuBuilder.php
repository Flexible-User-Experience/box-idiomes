<?php

namespace AppBundle\Menu;

use AppBundle\Repository\ContactMessageRepository;
use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

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
     * @var TokenStorageInterface
     */
    private $ts;

    /**
     * @var ContactMessageRepository
     */
    private $cmr;

    /**
     * Methods.
     */

    /**
     * Constructor.
     *
     * @param FactoryInterface         $factory
     * @param TokenStorageInterface    $ts
     * @param ContactMessageRepository $cmr
     */
    public function __construct(FactoryInterface $factory, TokenStorageInterface $ts, ContactMessageRepository $cmr)
    {
        $this->factory = $factory;
        $this->ts = $ts;
        $this->cmr = $cmr;
    }

    /**
     * @return ItemInterface
     */
    public function createTopNavMenu()
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
        if ($this->cmr->getNotReadMessagesAmount() > 0) {
            $menu
                ->addChild(
                    'messages',
                    array(
                        'label' => 'backend.admin.layout.top_nav_menu.logout',
                        'route' => 'admin_app_contactmessage_list',
                    )
                )
            ;
        }
        $menu
            ->addChild(
                'username',
                array(
                    'label' => $this->ts->getToken()->getUser()->getFullname(),
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
            ->setExtras(
                array(
                    'icon' => '<i class="fa fa-power-off text-success"></i>',
                )
            )
        ;

        return $menu;
    }
}
