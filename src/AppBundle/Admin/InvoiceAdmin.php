<?php

namespace AppBundle\Admin;

use AppBundle\Entity\Invoice;
use AppBundle\Enum\InvoiceYearMonthEnum;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\CoreBundle\Form\Type\DatePickerType;
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
    protected $baseRoutePattern = 'billings/invoice';
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
            ->add('generate')
            ->remove('delete');
    }

    /**
     * Get the list of actions that can be accessed directly from the dashboard.
     *
     * @return array
     */
    public function getDashboardActions()
    {
        $actions = parent::getDashboardActions();
        $actions['generate'] = array(
            'label' => 'backend.admin.invoice.generate_batch',
            'translation_domain' => 'messages',
            'url' => $this->generateUrl('generate'),
            'icon' => 'inbox',
        );

        return $actions;
    }

    /**
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->with('backend.admin.invoice.invoice', $this->getFormMdSuccessBoxArray(4))
            ->add(
                'year',
                ChoiceType::class,
                array(
                    'label' => 'backend.admin.invoice.year',
                    'required' => true,
                    'choices' => InvoiceYearMonthEnum::getYearEnumArray(),
                )
            )
            ->add(
                'month',
                ChoiceType::class,
                array(
                    'label' => 'backend.admin.invoice.month',
                    'required' => true,
                    'choices' => InvoiceYearMonthEnum::getMonthEnumArray(),
                    'choices_as_values' => false,
                )
            )
            ->add(
                'student',
                null,
                array(
                    'label' => 'backend.admin.invoice.student',
                    'required' => false,
                )
            )
            ->add(
                'person',
                null,
                array(
                    'label' => 'backend.admin.invoice.person',
                    'required' => false,
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
                'irpf',
                null,
                array(
                    'label' => 'backend.admin.invoice.irpf',
                    'required' => false,
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
                DatePickerType::class,
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
        if ($this->id($this->getSubject())) { // is edit mode, disable on new subjetcs
            $formMapper
                ->with('backend.admin.invoice.lines', $this->getFormMdSuccessBoxArray(12))
                ->add(
                    'lines',
                    'sonata_type_collection',
                    array(
                        'label' => 'Línia',
                        'required' => true,
                        'cascade_validation' => true,
                        'error_bubbling' => true,
                        'by_reference' => false,
                    ),
                    array(
                        'edit' => 'inline',
                        'inline' => 'table',
                    )
                )
                ->end()
            ;
        }
    }

    /**
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add(
                'year',
                null,
                array(
                    'label' => 'backend.admin.invoice.year',
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
                'totalAmount',
                null,
                array(
                    'label' => 'backend.admin.invoice.totalAmount',
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
                'doctrine_orm_date',
                array(
                    'label' => 'backend.admin.invoice.paymentDate',
                    'field_type' => 'sonata_type_date_picker',
                    'format' => 'd-m-Y',
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
                    'template' => '::Admin/Cells/list__cell_event_id.html.twig',
                )
            )
            ->add(
                'year',
                null,
                array(
                    'label' => 'backend.admin.invoice.year',
                    'editable' => false,
                )
            )
            ->add(
                'month',
                null,
                array(
                    'label' => 'backend.admin.invoice.month',
                    'template' => '::Admin/Cells/list__cell_event_month.html.twig',
                )
            )
            ->add(
                'student',
                null,
                array(
                    'label' => 'backend.admin.invoice.student',
                    'editable' => false,
                    'associated_property' => 'fullCanonicalName',
                    'sortable' => true,
                    'sort_field_mapping' => array('fieldName' => 'surname'),
                    'sort_parent_association_mappings' => array(array('fieldName' => 'student')),
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
                'isPayed',
                null,
                array(
                    'label' => 'backend.admin.invoice.isPayed',
                    'editable' => true,
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

    /**
     * @param Invoice $object
     */
    public function prePersist($object)
    {
        $this->commonPreActions($object);
    }

    /**
     * @param Invoice $object
     */
    public function preUpdate($object)
    {
        $this->commonPreActions($object);
    }

    /**
     * @param Invoice $object
     */
    private function commonPreActions($object)
    {
        $object->setTotalAmount($object->calculateTotalBaseAmount());
    }
}
