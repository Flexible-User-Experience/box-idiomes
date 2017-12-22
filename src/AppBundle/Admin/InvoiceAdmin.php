<?php

namespace AppBundle\Admin;

use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

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
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->with('backend.admin.invoice.invoice', $this->getFormMdSuccessBoxArray(4))
//            ->add(
//                'date',
//                'sonata_type_date_picker',
//                array(
//                    'label' => 'backend.admin.invoice.date',
//                    'format' => 'd/M/y',
//                    'required' => true,
//                )
//            )
            ->add(
                'year',
                ChoiceType::class,
                array(
                    'label' => 'backend.admin.invoice.year',
                    'required' => true,
                    'choices' => array(
                        '2018' => 2018,
                        '2017' => 2017,
                        '2016' => 2016,
                        '2015' => 2015,
                    ),
                )
            )
            ->add(
                'month',
                ChoiceType::class,
                array(
                    'label' => 'backend.admin.invoice.month',
                    'required' => true,
                    'choices' => array(
                        'Gener' => 1,
                        'Febrer' => 2,
                        'Març' => 3,
                        'Abril' => 4,
                        'Maig' => 5,
                        'Juny' => 6,
                        'Juiol' => 7,
                        'Agost' => 8,
                        'Setembre' => 9,
                        'Octubre' => 10,
                        'Novembre' => 11,
                        'Desembre' => 12,
                    ),
                    'choices_as_values' => true,
                )
            )
            ->add(
                'student',
                null,
                array(
                    'label' => 'backend.admin.invoice.student',
                    'required' => true,
                )
            )
            ->add(
                'person',
                null,
                array(
                    'label' => 'backend.admin.invoice.person',
                    'required' => true,
                )
            )
            ->end()
            ->with('backend.admin.invoice.detail', $this->getFormMdSuccessBoxArray(4))
            ->add(
                'baseAmount',
                null,
                array(
                    'label' => 'backend.admin.invoice.baseAmount',
                    'required' => true,
                )
            )
            ->add(
                'taxParcentage',
                null,
                array(
                    'label' => 'backend.admin.invoice.taxParcentage',
                    'required' => true,
                )
            )
            ->add(
                'totalAmount',
                null,
                array(
                    'label' => 'backend.admin.invoice.totalAmount',
                    'required' => true,
                    'disabled' => true,
                )
            )
            ->end()
            ->with('backend.admin.controls', $this->getFormMdSuccessBoxArray(3))
            ->add(
                'paymentDate',
                'sonata_type_date_picker',
                array(
                    'label' => 'backend.admin.invoice.paymentDate',
                    'format' => 'd/M/y',
                    'required' => false,
                )
            )
            ->add(
                'discountApplied',
                null,
                array(
                    'label' => 'backend.admin.invoice.discountApplied',
                    'required' => false,
                )
            )
            ->add(
                'isPayed',
                CheckboxType::class,
                array(
                    'label' => 'backend.admin.invoice.isPayed',
                    'required' => false,
                )
            )
            ->end();
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
            );
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
//            ->add(
//                'date',
//                null,
//                array(
//                    'label' => 'backend.admin.invoice.date',
//                    'editable' => true,
//                    'format' => 'd/m/Y',
//                )
//            )
            ->add(
                'year',
                null,
                array(
                    'label' => 'backend.admin.invoice.year',
                    'editable' => true,
                )
            )
            ->add(
                'month',
                null,
                array(
                    'label' => 'backend.admin.invoice.month',
                    'editable' => true,
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
//            ->add(
//                'baseAmount',
//                null,
//                array(
//                    'label' => 'backend.admin.invoice.baseAmount',
//                    'editable' => false,
//                )
//            )
//            ->add(
//                'taxParcentage',
//                null,
//                array(
//                    'label' => 'backend.admin.invoice.taxParcentage',
//                    'editable' => false,
//                )
//            )
            ->add(
                'totalAmount',
                null,
                array(
                    'label' => 'backend.admin.invoice.totalAmount',
                    'editable' => false,
                )
            )
            ->add(
                'isPayed',
                null,
                array(
                    'label' => 'backend.admin.invoice.isPayed',
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
            );
    }
}
