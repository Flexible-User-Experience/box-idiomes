<?php

namespace AppBundle\Admin;

use AppBundle\Entity\Person;
use AppBundle\Entity\Receipt;
use AppBundle\Entity\Student;
use AppBundle\Enum\InvoiceYearMonthEnum;
use AppBundle\Enum\StudentPaymentEnum;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\CoreBundle\Form\Type\DatePickerType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

/**
 * Class ReceiptAdmin.
 *
 * @category Admin
 */
class ReceiptAdmin extends AbstractBaseAdmin
{
    protected $classnameLabel = 'Receipt';
    protected $baseRoutePattern = 'billings/receipt';
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
            ->add('generate')
            ->add('creator')
            ->add('createInvoice', $this->getRouterIdParameter().'/create-invoice')
            ->add('reminder', $this->getRouterIdParameter().'/reminder-pdf')
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
            'label' => 'backend.admin.receipt.generate_batch',
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
        $now = new \DateTime();
        $currentYear = $now->format('Y');

        $formMapper
            ->with('backend.admin.receipt.receipt', $this->getFormMdSuccessBoxArray(3))
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
                'discountApplied',
                null,
                array(
                    'label' => 'backend.admin.invoice.discountApplied',
                    'required' => false,
                    'disabled' => true,
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
            ->end()
            ->with('backend.admin.controls', $this->getFormMdSuccessBoxArray(3))
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
                    'label' => 'backend.admin.receipt.isSended',
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
                    'label' => 'backend.admin.receipt.isPayed',
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
                ->with('backend.admin.receipt.lines', $this->getFormMdSuccessBoxArray(12))
                ->add(
                    'lines',
                    'sonata_type_collection',
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
                    'label' => 'backend.admin.receipt.id',
                )
            )
            ->add(
                'date',
                'doctrine_orm_date',
                array(
                    'label' => 'backend.admin.receipt.date',
                    'field_type' => 'sonata_type_date_picker',
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
                    'label' => 'backend.admin.receipt.isSended',
                )
            )
            ->add(
                'sendDate',
                'doctrine_orm_date',
                array(
                    'label' => 'backend.admin.invoice.sendDate',
                    'field_type' => 'sonata_type_date_picker',
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
                    'label' => 'backend.admin.receipt.isPayed',
                )
            )
            ->add(
                'paymentDate',
                'doctrine_orm_date',
                array(
                    'label' => 'backend.admin.invoice.paymentDate',
                    'field_type' => 'sonata_type_date_picker',
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
                    'label' => 'backend.admin.receipt.id',
                    'template' => '::Admin/Cells/list__cell_receipt_number.html.twig',
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
                    'template' => '::Admin/Cells/list__cell_receipt_amount.html.twig',
                    'editable' => false,
                )
            )
            ->add(
                'isForPrivateLessons',
                null,
                array(
                    'label' => 'backend.admin.is_for_private_lessons',
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
                    'label' => 'backend.admin.receipt.isSended',
                    'editable' => false,
                )
            )
            ->add(
                'isPayed',
                null,
                array(
                    'label' => 'backend.admin.receipt.isPayed',
                    'editable' => false,
                )
            )
            ->add(
                '_action',
                'actions',
                array(
                    'actions' => array(
                        'edit' => array('template' => '::Admin/Buttons/list__action_edit_button.html.twig'),
                        'pdf' => array('template' => '::Admin/Buttons/list__action_receipt_pdf_button.html.twig'),
                        'send' => array('template' => '::Admin/Buttons/list__action_receipt_send_button.html.twig'),
                        'createInvoice' => array('template' => '::Admin/Buttons/list__action_receipt_create_invoice_button.html.twig'),
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
            'receiptNumber',
            'dateString',
            'year',
            'month',
            'student.fullCanonicalName',
            'person.fullCanonicalName',
            'student.paymentString',
            'discountApplied',
            'baseAmountString',
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
     * @param Receipt $object
     */
    public function prePersist($object)
    {
        $this->commonPreActions($object);
    }

    /**
     * @param Receipt $object
     */
    public function preUpdate($object)
    {
        $this->commonPreActions($object);
    }

    /**
     * @param Receipt $object
     */
    private function commonPreActions($object)
    {
        if ($object->getStudent()->getParent()) {
            $object->setPerson($object->getStudent()->getParent());
        }
        $object->setBaseAmount($object->calculateTotalBaseAmount());
    }
}
