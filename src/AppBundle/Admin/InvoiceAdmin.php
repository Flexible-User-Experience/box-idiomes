<?php

namespace AppBundle\Admin;

use AppBundle\Entity\Invoice;
use AppBundle\Entity\Person;
use AppBundle\Entity\Receipt;
use AppBundle\Entity\Student;
use AppBundle\Enum\InvoiceYearMonthEnum;
use AppBundle\Enum\StudentPaymentEnum;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\Form\Type\DatePickerType;
use Sonata\Form\Type\CollectionType;
use Sonata\DoctrineORMAdminBundle\Filter\DateFilter;
use Sonata\DoctrineORMAdminBundle\Filter\ModelAutocompleteFilter;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

/**
 * Class InvoiceAdmin.
 *
 * @category Admin
 */
class InvoiceAdmin extends AbstractBaseAdmin
{
    protected $classnameLabel = 'Invoice';
    protected $baseRoutePattern = 'billings/invoice';
    protected $maxPerPage = 400;
    protected $datagridValues = array(
        '_sort_by' => 'id',
        '_sort_order' => 'desc',
        '_per_page' => 400,
    );

    /**
     * Configure route collection.
     *
     * @param RouteCollection $collection
     */
    protected function configureRoutes(RouteCollection $collection)
    {
        $collection
            ->add('pdf', $this->getRouterIdParameter().'/pdf')
            ->add('send', $this->getRouterIdParameter().'/send')
            ->add('generateDirectDebit', $this->getRouterIdParameter().'/generate-direct-debit-xml')
            ->remove('show')
        ;
    }

    /**
     * @param array $actions
     *
     * @return array
     */
    public function configureBatchActions($actions)
    {
        if ($this->hasRoute('edit') && $this->hasAccess('edit')) {
            $actions['generatesepaxmls'] = array(
                'label' => 'backend.admin.invoice.batch_action',
                'translation_domain' => 'messages',
                'ask_confirmation' => false,
            );
        }

        return $actions;
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
     *
     * @throws \Exception
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $now = new \DateTime();
        $currentYear = $now->format('Y');

        $formMapper
            ->with('backend.admin.invoice.invoice', $this->getFormMdSuccessBoxArray(3))
            ->add(
                'date',
                DatePickerType::class,
                array(
                    'label' => 'backend.admin.receipt.date',
                    'format' => 'd/M/y',
                    'required' => $this->id($this->getSubject()) ? false : true,
                    'disabled' => $this->id($this->getSubject()) ? true : false,
                )
            )
            ->add(
                'year',
                ChoiceType::class,
                array(
                    'label' => 'backend.admin.invoice.year',
                    'required' => true,
                    'choices' => InvoiceYearMonthEnum::getYearEnumArray(),
                    'preferred_choices' => $currentYear,
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
                EntityType::class,
                array(
                    'label' => 'backend.admin.invoice.student',
                    'required' => true,
                    'class' => Student::class,
                    'choice_label' => 'fullCanonicalName',
                    'query_builder' => $this->getConfigurationPool()->getContainer()->get('app.student_repository')->getEnabledSortedBySurnameValidTariffQB(),
                )
            )
            ->add(
                'person',
                EntityType::class,
                array(
                    'label' => 'backend.admin.invoice.person',
                    'required' => false,
                    'class' => Person::class,
                    'choice_label' => 'fullCanonicalName',
                    'query_builder' => $this->getConfigurationPool()->getContainer()->get('app.parent_repository')->getEnabledSortedBySurnameQB(),
                    'disabled' => true,
                )
            )
            ->end()
            ->with('backend.admin.invoice.detail', $this->getFormMdSuccessBoxArray(3))
            ->add(
                'taxPercentage',
                null,
                array(
                    'label' => 'backend.admin.invoice.taxPercentage',
                    'required' => false,
                    'help' => '%',
                )
            )
            ->add(
                'irpfPercentage',
                null,
                array(
                    'label' => 'backend.admin.invoice.irpfPercentage',
                    'required' => false,
                    'help' => '%',
                )
            )
            ->add(
                'baseAmount',
                null,
                array(
                    'label' => 'backend.admin.invoice.baseAmount',
                    'required' => false,
                    'disabled' => true,
                )
            )
            ->add(
                'totalAmount',
                null,
                array(
                    'label' => 'backend.admin.invoice.totalAmount',
                    'required' => false,
                    'disabled' => true,
                )
            )
            ->end()
            ->with('backend.admin.controls', $this->getFormMdSuccessBoxArray(3))
            ->add(
                'receipt',
                EntityType::class,
                array(
                    'label' => 'backend.admin.invoice.receipt',
                    'required' => false,
                    'class' => Receipt::class,
                    'query_builder' => $this->getConfigurationPool()->getContainer()->get('app.receipt_repository')->getAllSortedByNumberDescQB(),
                )
            )
            ->add(
                'discountApplied',
                null,
                array(
                    'label' => 'backend.admin.invoice.discountApplied',
                    'required' => false,
                    'disabled' => true,
                )
            )
            ->add(
                'isForPrivateLessons',
                CheckboxType::class,
                array(
                    'label' => 'backend.admin.is_for_private_lessons',
                    'required' => false,
                    'disabled' => false,
                )
            )
            ->add(
                'isSepaXmlGenerated',
                CheckboxType::class,
                array(
                    'label' => 'backend.admin.receipt.isSepaXmlGenerated',
                    'required' => false,
                    'disabled' => true,
                )
            )
            ->add(
                'sepaXmlGeneratedDate',
                DatePickerType::class,
                array(
                    'label' => 'backend.admin.receipt.sepaXmlGeneratedDate',
                    'format' => 'd/M/y',
                    'required' => false,
                    'disabled' => true,
                )
            )
            ->add(
                'isSended',
                CheckboxType::class,
                array(
                    'label' => 'backend.admin.invoice.isSended',
                    'required' => false,
                    'disabled' => true,
                )
            )
            ->add(
                'sendDate',
                DatePickerType::class,
                array(
                    'label' => 'backend.admin.invoice.sendDate',
                    'format' => 'd/M/y',
                    'required' => false,
                    'disabled' => true,
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
            ->add(
                'paymentDate',
                DatePickerType::class,
                array(
                    'label' => 'backend.admin.invoice.paymentDate',
                    'format' => 'd/M/y',
                    'required' => false,
                )
            )
            ->end();
        if ($this->id($this->getSubject())) { // is edit mode, disable on new subjetcs
            $formMapper
                ->with('backend.admin.invoice.lines', $this->getFormMdSuccessBoxArray(12))
                ->add(
                    'lines',
                    CollectionType::class,
                    array(
                        'label' => 'backend.admin.invoice.line',
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
                'id',
                null,
                array(
                    'label' => 'backend.admin.invoice.id',
                )
            )
            ->add(
                'date',
                DateFilter::class,
                array(
                    'label' => 'backend.admin.receipt.date',
                    'field_type' => DatePickerType::class,
                    'format' => 'd-m-Y',
                ),
                null,
                array(
                    'widget' => 'single_text',
                    'format' => 'dd-MM-yyyy',
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
                'month',
                null,
                array(
                    'label' => 'backend.admin.invoice.month',
                ),
                ChoiceType::class,
                array(
                    'choices' => InvoiceYearMonthEnum::getMonthEnumArray(),
                    'choices_as_values' => false,
                    'expanded' => false,
                    'multiple' => false,
                )
            )
            ->add(
                'receipt',
                ModelAutocompleteFilter::class,
                array(
                    'label' => 'backend.admin.invoice.receipt',
                ),
                null,
                array(
                    'class' => Receipt::class,
                    'property' => array('month', 'year'),
                )
            )
            ->add(
                'student',
                ModelAutocompleteFilter::class,
                array(
                    'label' => 'backend.admin.invoice.student',
                ),
                null,
                array(
                    'class' => Student::class,
                    'property' => array('name', 'surname'),
                )
            )
            ->add(
                'person',
                ModelAutocompleteFilter::class,
                array(
                    'label' => 'backend.admin.invoice.person',
                ),
                null,
                array(
                    'class' => Person::class,
                    'property' => array('name', 'surname'),
                )
            )
            ->add(
                'student.payment',
                null,
                array(
                    'label' => 'backend.admin.parent.payment',
                ),
                ChoiceType::class,
                array(
                    'choices' => StudentPaymentEnum::getEnumArray(),
                    'choices_as_values' => false,
                    'expanded' => false,
                    'multiple' => false,
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
                'baseAmount',
                null,
                array(
                    'label' => 'backend.admin.invoice.baseAmount',
                )
            )
            ->add(
                'taxPercentage',
                null,
                array(
                    'label' => 'backend.admin.invoice.taxPercentage',
                )
            )
            ->add(
                'irpfPercentage',
                null,
                array(
                    'label' => 'backend.admin.invoice.irpfPercentage',
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
                'isForPrivateLessons',
                null,
                array(
                    'label' => 'backend.admin.is_for_private_lessons',
                )
            )
            ->add(
                'isSepaXmlGenerated',
                null,
                array(
                    'label' => 'backend.admin.receipt.isSepaXmlGenerated',
                )
            )
            ->add(
                'sepaXmlGeneratedDate',
                null,
                array(
                    'label' => 'backend.admin.receipt.sepaXmlGeneratedDate',
                )
            )
            ->add(
                'isSended',
                null,
                array(
                    'label' => 'backend.admin.invoice.isSended',
                )
            )
            ->add(
                'sendDate',
                DateFilter::class,
                array(
                    'label' => 'backend.admin.invoice.sendDate',
                    'field_type' => DatePickerType::class,
                    'format' => 'd-m-Y',
                ),
                null,
                array(
                    'widget' => 'single_text',
                    'format' => 'dd-MM-yyyy',
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
                DateFilter::class,
                array(
                    'label' => 'backend.admin.invoice.paymentDate',
                    'field_type' => DatePickerType::class,
                    'format' => 'd-m-Y',
                ),
                null,
                array(
                    'widget' => 'single_text',
                    'format' => 'dd-MM-yyyy',
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
                    'template' => '::Admin/Cells/list__cell_invoice_number.html.twig',
                )
            )
            ->add(
                'date',
                null,
                array(
                    'label' => 'backend.admin.receipt.date',
                    'template' => '::Admin/Cells/list__cell_receipt_date.html.twig',
                    'editable' => false,
                )
            )
            ->add(
                'year',
                null,
                array(
                    'label' => 'backend.admin.invoice.year',
                    'template' => '::Admin/Cells/list__cell_event_year.html.twig',
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
                'receipt',
                null,
                array(
                    'label' => 'backend.admin.invoice.receipt',
                    'editable' => false,
                    'associated_property' => 'receiptNumber',
                    'sortable' => true,
                    'sort_field_mapping' => array('fieldName' => 'id'),
                    'sort_parent_association_mappings' => array(array('fieldName' => 'receipt')),
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
                'baseAmount',
                null,
                array(
                    'label' => 'backend.admin.invoice.baseAmount',
                    'template' => '::Admin/Cells/list__cell_invoice_base_amount.html.twig',
                    'editable' => false,
                )
            )
            ->add(
                'totalAmount',
                null,
                array(
                    'label' => 'backend.admin.invoice.totalAmount',
                    'template' => '::Admin/Cells/list__cell_invoice_total_amount.html.twig',
                    'editable' => false,
                )
            )
            ->add(
                'isSepaXmlGenerated',
                null,
                array(
                    'label' => 'backend.admin.receipt.isSepaXmlGenerated',
                    'editable' => false,
                )
            )
            ->add(
                'isSended',
                null,
                array(
                    'label' => 'backend.admin.invoice.isSended',
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
                        'invoice' => array('template' => '::Admin/Buttons/list__action_invoice_pdf_button.html.twig'),
                        'send' => array('template' => '::Admin/Buttons/list__action_invoice_send_button.html.twig'),
                        'generateDirectDebit' => array('template' => '::Admin/Buttons/list__action_generate_direct_debit_xml_button.html.twig'),
                        'delete' => array('template' => '::Admin/Buttons/list__action_delete_superadmin_button.html.twig'),
                    ),
                    'label' => 'backend.admin.actions',
                )
            );
    }

    /**
     * @return array
     */
    public function getExportFields()
    {
        return array(
            'invoiceNumber',
            'dateString',
            'year',
            'month',
            'receipt.receiptNumber',
            'student.fullCanonicalName',
            'person.fullCanonicalName',
            'student.paymentString',
            'discountApplied',
            'baseAmountString',
            'taxPercentage',
            'irpfPercentage',
            'totalAmountString',
            'isForPrivateLessons',
            'isSepaXmlGenerated',
            'sepaXmlGeneratedDateString',
            'isSended',
            'sendDateString',
            'isPayed',
            'paymentDateString',
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
        if ($object->getStudent()->getParent()) {
            $object->setPerson($object->getStudent()->getParent());
        }
        $object
            ->setBaseAmount($object->calculateBaseAmount())
            ->setTotalAmount($object->calculateTotalAmount())
        ;
    }
}
