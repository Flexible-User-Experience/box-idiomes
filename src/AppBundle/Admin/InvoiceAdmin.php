<?php

namespace AppBundle\Admin;

use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Route\RouteCollection;

/**
 * Class InvoiceAdmin.
 *
 * @category Admin
 *
 * @author   Wils Iglesias <wiglesias83@gmail.com>
 */
class InvoiceAdmin extends AbstractBaseAdmin
{
    protected $classnameLabel = 'Invoice';
    protected $baseRoutePattern = 'billing/invoice';
    protected $datagridValues = array(
        '_sort_by' => 'id',
        '_sort_order' => 'desc',
    );

    /**
     * Configure route collection.
     *
     * @param RouteCollection $collection
     */
    protected function configureRoutes(RouteCollection $collection)
    {
        parent::configureRoutes($collection);
        $collection
            ->remove('delete');
    }

    /**
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add(
                'student',
                null,
                array(
                    'label' => 'backend.admin.invoice.student',
                )
            )
            ->add(
                'person',
                null,
                array(
                    'label' => 'backend.admin.invoice.person',
                )
            )
            ->add(
                'date',
                'doctrine_orm_date',
                array(
                    'label' => 'backend.admin.invoice.date',
                    'field_type' => 'sonata_type_date_picker',
                    'format' => 'd-m-Y',
                )
            )
            ->add(
                'isPayed',
                null,
                array(
                    'label' => 'backend.admin.invoice.isPayed',
                )
            )
            ->add(
                'paymentDate',
                null,
                array(
                    'label' => 'backend.admin.invoice.paymentDate',
                )
            )
            ->add(
                'taxParcentage',
                null,
                array(
                    'label' => 'backend.admin.invoice.taxParcentage',
                )
            )
            ->add(
                'discountApplied',
                null,
                array(
                    'label' => 'backend.admin.invoice.discountApplied',
                )
            )
            ->add(
                'month',
                null,
                array(
                    'label' => 'backend.admin.invoice.month',
                )
            )
            ->add(
                'year',
                null,
                array(
                    'label' => 'backend.admin.invoice.year',
                )
            )
            ->add(
                'enabled',
                null,
                array(
                    'label' => 'backend.admin.enabled',
                )
            )
        ;
    }

    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        unset($this->listModes['mosaic']);
        $listMapper
            ->add(
                'id',
                null,
                array(
                    'label' => 'backend.admin.invoice.id',
                    'editable' => false,
                )
            )
            ->add(
                'date',
                null,
                array(
                    'label' => 'backend.admin.invoice.date',
                    'editable' => true,
                    'format' => 'd/m/Y',
                )
            )
            ->add(
                'student',
                null,
                array(
                    'label' => 'backend.admin.invoice.student',
                    'editable' => true,
                )
            )
            ->add(
                'person',
                null,
                array(
                    'label' => 'backend.admin.invoice.person',
                    'editable' => true,
                )
            )
            ->add(
                'baseAmount',
                null,
                array(
                    'label' => 'backend.admin.invoice.baseAmount',
                    'editable' => false,
                )
            )
            ->add(
                'taxParcentage',
                null,
                array(
                    'label' => 'backend.admin.invoice.taxParcentage',
                    'editable' => false,
                )
            )
            ->add(
                'totalAmount',
                null,
                array(
                    'label' => 'backend.admin.invoice.totalAmount',
                    'editable' => false,
                )
            )
            ->add(
                '_action',
                'actions',
                array(
                    'actions' => array(
                        'edit' => array('template' => '::Admin/Buttons/list__action_edit_button.html.twig'),
                    ),
                    'label' => 'backend.admin.actions',
                )
            )
        ;
    }
}
