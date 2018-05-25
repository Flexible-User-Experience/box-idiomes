<?php

namespace AppBundle\Menu;

use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;

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
     * @return ItemInterface
     */
    public function createSideMenu()
    {
        $menu = $this->factory->createItem('Facturació');
        $menu
            ->addChild(
                'tariffs',
                array(
                    'label' => 'backend.admin.student.tariff',
                    'route' => 'admin_app_tariff_list',
                )
            )
        ;
        $menu
            ->addChild(
                'invoices',
                array(
                    'label' => 'backend.admin.invoice.invoice',
                    'route' => 'admin_app_invoice_list',
                )
            )
        ;
        $menu
            ->addChild(
                'generator',
                array(
                    'label' => 'backend.admin.invoice.generate_batch',
                    'route' => 'admin_app_invoice_generate',
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
