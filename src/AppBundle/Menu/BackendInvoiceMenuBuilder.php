<?php

namespace AppBundle\Menu;

use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Class BackendInvoiceMenuBuilder.
 *
 * @category Menu
 */
class BackendInvoiceMenuBuilder
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
    public function createSideMenu(RequestStack $requestStack)
    {
        $route = $requestStack->getCurrentRequest()->get('_route');
        $menu = $this->factory->createItem('Facturació');
        $menu
            ->addChild(
                'tariffs',
                array(
                    'label' => 'backend.admin.student.tariff',
                    'route' => 'admin_app_tariff_list',
                    'current' => 'admin_app_tariff_list' == $route || 'admin_app_tariff_edit' == $route,
                )
            )
        ;
        $menu
            ->addChild(
                'invoices',
                array(
                    'label' => 'backend.admin.invoice.invoice',
                    'route' => 'admin_app_invoice_list',
                    'current' => 'admin_app_invoice_list' == $route || 'admin_app_invoice_edit' == $route,
                )
            )
        ;
        $menu
            ->addChild(
                'generator',
                array(
                    'label' => 'backend.admin.invoice.generate_batch',
                    'route' => 'admin_app_invoice_generate',
                    'current' => 'admin_app_invoice_generate' == $route,
                )
            )
            ->setExtras(
                array(
                    'icon' => '<i class="fa fa-inbox"></i>',
                )
            )
        ;

        return $menu;
    }
}
