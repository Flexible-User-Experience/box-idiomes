<?php

namespace AppBundle\Menu;

use AppBundle\Enum\UserRolesEnum;
use AppBundle\Repository\ContactMessageRepository;
use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationChecker;

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
     * @var AuthorizationChecker
     */
    private $ac;

    /**
     * @var ContactMessageRepository
     */
    private $cmr;

    /**
     * Methods.
     */

    /**
     * Constructor.
     */
    public function __construct(FactoryInterface $factory, TokenStorageInterface $ts, AuthorizationChecker $ac, ContactMessageRepository $cmr)
    {
        $this->factory = $factory;
        $this->ts = $ts;
        $this->ac = $ac;
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
                [
                    'label' => '<i class="fa fa-globe"></i>',
                    'route' => 'app_homepage',
                ]
            )
            ->setExtras(
                [
                    'safe_label' => true,
                ]
            )
        ;
        if ($this->cmr->getNotReadMessagesAmount() > 0) {
            $menu
                ->addChild(
                    'messages',
                    [
                        'label' => '<i class="fa fa-envelope text-danger"></i> <span class="text-danger">'.$this->cmr->getNotReadMessagesAmount().'</span>',
                        'route' => 'admin_app_contactmessage_list',
                    ]
                )
                ->setExtras(
                    [
                        'safe_label' => true,
                    ]
                )
            ;
        }
        if ($this->ac->isGranted(UserRolesEnum::ROLE_ADMIN)) {
            $menu
                ->addChild(
                    'username',
                    [
                        'label' => $this->ts->getToken()->getUser()->getFullname(),
                        'route' => 'admin_app_user_edit',
                        'routeParameters' => [
                            'id' => $this->ts->getToken()->getUser()->getId(),
                        ],
                    ]
                )
            ;
        } else {
            $menu
                ->addChild(
                    'username',
                    [
                        'label' => $this->ts->getToken()->getUser()->getFullname(),
                        'uri' => '#',
                    ]
                )
            ;
        }
        $menu
            ->addChild(
                'logout',
                [
                    'label' => '<i class="fa fa-power-off text-success"></i>',
                    'route' => 'sonata_user_admin_security_logout',
                ]
            )
            ->setExtras(
                [
                    'safe_label' => true,
                ]
            )
        ;

        return $menu;
    }
}
