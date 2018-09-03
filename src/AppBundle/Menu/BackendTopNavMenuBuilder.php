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
                    'label' => '<i class="fa fa-globe"></i>',
                    'route' => 'app_homepage',
                )
            )
            ->setExtras(
                array(
                    'safe_label' => true,
                )
            )
        ;
        if ($this->cmr->getNotReadMessagesAmount() > 0) {
            $menu
                ->addChild(
                    'messages',
                    array(
                        'label' => '<i class="fa fa-bell text-danger"></i> <span class="badge">'.$this->cmr->getNotReadMessagesAmount().'</span>',
                        'route' => 'admin_app_contactmessage_list',
                    )
                )
                ->setExtras(
                    array(
                        'safe_label' => true,
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
                    'label' => '<i class="fa fa-power-off text-success"></i>',
                    'route' => 'sonata_user_admin_security_logout',
                )
            )
            ->setExtras(
                array(
                    'safe_label' => true,
                )
            )
        ;

        return $menu;
    }
}
