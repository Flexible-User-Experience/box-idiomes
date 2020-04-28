<?php

namespace AppBundle\Menu;

use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Class BackendFilesManagerMenuBuilder.
 *
 * @category Menu
 */
class BackendFilesManagerMenuBuilder
{
    /**
     * @var FactoryInterface
     */
    private $factory;

    /**
     * Methods.
     */

    /**
     * Constructor.
     *
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
    public function createSideMenu(RequestStack $requestStack)
    {
        $route = $requestStack->getCurrentRequest()->get('_route');
        $menu = $this->factory->createItem('Fitxers');
        $menu
            ->addChild(
                'files',
                array(
                    'label' => 'backend.admin.files',
                    'route' => 'file_manager_sonata',
                    'current' => 'file_manager_sonata' == $route || 'file_manager' == $route || 'file_manager_rename' == $route || 'file_manager_upload' == $route,
                )
            )
        ;

        return $menu;
    }
}
