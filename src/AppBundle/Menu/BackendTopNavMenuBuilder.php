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
        $route = $requestStack->getCurrentRequest()->get('_route');
        $menu = $this->factory->createItem('Facturació');
        $menu
            ->addChild(
                'tariffs',
                array(
                    'label' => 'backend.admin.student.tariff',
                    'route' => 'admin_app_tariff_list',
                    'current' => 'admin_app_tariff_list' == $route || 'admin_app_tariff_create' == $route || 'admin_app_tariff_edit' == $route,
                )
            )
        ;
        $menu
            ->addChild(
                'receipts',
                array(
                    'label' => 'backend.admin.receipt.receipt',
                    'route' => 'admin_app_receipt_list',
                    'current' => 'admin_app_receipt_list' == $route || 'admin_app_receipt_create' == $route || 'admin_app_receipt_edit' == $route,
                )
            )
        ;
        $menu
            ->addChild(
                'generator',
                array(
                    'label' => 'backend.admin.receipt.generate_batch',
                    'route' => 'admin_app_receipt_generate',
                    'current' => 'admin_app_receipt_generate' == $route,
                )
            )
            ->setExtras(
                array(
                    'icon' => '<i class="fa fa-inbox"></i>',
                )
            )
        ;
        $menu
            ->addChild(
                'invoices',
                array(
                    'label' => 'backend.admin.invoice.invoice',
                    'route' => 'admin_app_invoice_list',
                    'current' => 'admin_app_invoice_list' == $route || 'admin_app_invoice_create' == $route || 'admin_app_invoice_edit' == $route,
                )
            )
        ;

        return $menu;
    }
}
