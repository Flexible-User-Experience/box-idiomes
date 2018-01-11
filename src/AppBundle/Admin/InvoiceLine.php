<?php

namespace AppBundle\Admin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

/**
 * Class InvoiceLine
 *
 * @category Admin
 * @package  AppBundle\Admin
 * @author   Wils Iglesias <wiglesias83@gmail.com>
 */
class InvoiceLine extends AbstractBaseAdmin
{
    protected $classnameLabel = 'Invoice';
    protected $baseRoutePattern = 'billings/invoice-line';
    protected $datagridValues = array(
        '_sort_by' => 'description',
        '_sort_order' => 'asc',
    );

    /**
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->with('backend.admin.general', $this->getFormMdSuccessBoxArray(6))
            ->add(
                'description',
                null,
                array(
                    'label' => 'backend.admin.invoiceLine.description',
                )
            )
            ->add(
                'units',
                null,
                array(
                    'label' => 'backend.admin.invoiceLine.units',
                )
            )
            ->add(
                'priceUnit',
                null,
                array(
                    'label' => 'backend.admin.invoiceLine.priceUnit',
                )
            )
            ->add(
                'discount',
                null,
                array(
                    'label' => 'backend.admin.invoiceLine.discount',
                )
            )
            ->add(
                'total',
                null,
                array(
                    'label' => 'backend.admin.invoiceLine.total',
                )
            )
            ->end()
            ->with('backend.admin.controls', $this->getFormMdSuccessBoxArray(6))
            ->add(
                'invoice',
                null,
                array(
                    'label' => 'backend.admin.invoiceLine.invoice',
                    'attr' => array(
                        'hidden' => true,
                    ),
                    'required' => true,
                )
            )
            ->add(
                'enabled',
                CheckboxType::class,
                array(
                    'label' => 'backend.admin.enabled',
                    'required' => false,
                    'attr' => array(
                        'hidden' => true,
                    ),
                )
            )
            ->end()
        ;
    }

    /**
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add(
                'invoice',
                null,
                array(
                    'label' => 'backend.admin.invoiceLine.invoice',
                )
            )
            ->add(
                'description',
                null,
                array(
                    'label' => 'backend.admin.invoiceLine.description',
                )
            )
            ->add(
                'units',
                null,
                array(
                    'label' => 'backend.admin.invoiceLine.units',
                )
            )
            ->add(
                'priceUnit',
                null,
                array(
                    'label' => 'backend.admin.invoiceLine.priceUnit',
                )
            )
            ->add(
                'discount',
                null,
                array(
                    'label' => 'backend.admin.invoiceLine.discount',
                )
            )
            ->add(
                'total',
                null,
                array(
                    'label' => 'backend.admin.invoiceLine.total',
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
                'invoice',
                null,
                array(
                    'label' => 'backend.admin.invoiceLine.invoice',
                    'editable' => true,
                )
            )
            ->add(
                'description',
                null,
                array(
                    'label' => 'backend.admin.invoiceLine.description',
                    'editable' => true,
                )
            )
            ->add(
                'units',
                null,
                array(
                    'label' => 'backend.admin.invoiceLine.units',
                    'editable' => true,
                )
            )
            ->add(
                'priceUnit',
                null,
                array(
                    'label' => 'backend.admin.invoiceLine.priceUnit',
                    'editable' => true,
                )
            )
            ->add(
                'discount',
                null,
                array(
                    'label' => 'backend.admin.invoiceLine.discount',
                    'editable' => true,
                )
            )
            ->add(
                'total',
                null,
                array(
                    'label' => 'backend.admin.invoiceLine.total',
                    'editable' => true,
                )
            )
            ->add(
                'enabled',
                null,
                array(
                    'label' => 'backend.admin.enabled',
                    'editable' => true,
                )
            )
            ->add(
                '_action',
                'actions',
                array(
                    'actions' => array(
                        'show' => array('template' => '::Admin/Buttons/list__action_show_button.html.twig'),
                        'edit' => array('template' => '::Admin/Buttons/list__action_edit_button.html.twig'),
                        'delete' => array('template' => '::Admin/Buttons/list__action_delete_button.html.twig'),
                    ),
                    'label' => 'backend.admin.actions',
                )
            )
        ;
    }
}
