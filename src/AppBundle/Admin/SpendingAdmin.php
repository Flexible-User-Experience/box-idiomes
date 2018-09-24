<?php

namespace AppBundle\Admin;

use AppBundle\Entity\Provider;
use AppBundle\Entity\SpendingCategory;
use AppBundle\Enum\StudentPaymentEnum;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\CoreBundle\Form\Type\DatePickerType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;

/**
 * Class SpendingAdmin.
 *
 * @category Admin
 */
class SpendingAdmin extends AbstractBaseAdmin
{
    protected $classnameLabel = 'Spending';
    protected $baseRoutePattern = 'purchases/spending';
    protected $datagridValues = array(
        '_sort_by' => 'date',
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
        $collection->add('duplicate', $this->getRouterIdParameter().'/duplicate');
    }

    /**
     * @param FormMapper $formMapper
     *
     * @throws \Twig\Error\Error
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->with('backend.admin.general', $this->getFormMdSuccessBoxArray(5))
            ->add(
                'date',
                DatePickerType::class,
                array(
                    'label' => 'backend.admin.spending.date',
                    'format' => 'd/M/y',
                    'required' => true,
                )
            )
            ->add(
                'category',
                EntityType::class,
                array(
                    'label' => 'backend.admin.spending.category',
                    'required' => false,
                    'class' => SpendingCategory::class,
                    'query_builder' => $this->getConfigurationPool()->getContainer()->get('app.spending_category_repository')->getEnabledSortedByNameQB(),
                )
            )
            ->add(
                'provider',
                EntityType::class,
                array(
                    'label' => 'backend.admin.spending.provider',
                    'required' => true,
                    'class' => Provider::class,
                    'query_builder' => $this->getConfigurationPool()->getContainer()->get('app.provider_repository')->getEnabledSortedByNameQB(),
                )
            )
            ->add(
                'description',
                null,
                array(
                    'label' => 'backend.admin.spending.description',
                    'required' => false,
                )
            )
            ->end()
            ->with('backend.admin.documents', $this->getFormMdSuccessBoxArray(4))
            ->add(
                'documentFile',
                FileType::class,
                array(
                    'label' => 'backend.admin.spending.document',
                    'help' => $this->getSmartHelper('getDocument', 'documentFile'),
                    'required' => false,
                )
            )
            ->end()
            ->with('backend.admin.controls', $this->getFormMdSuccessBoxArray(3))
            ->add(
                'baseAmount',
                null,
                array(
                    'label' => 'backend.admin.invoice.baseAmount',
                    'required' => true,
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
            ->add(
                'paymentMethod',
                ChoiceType::class,
                array(
                    'label' => 'backend.admin.customer.payment_method',
                    'choices' => StudentPaymentEnum::getEnumArray(),
                    'required' => true,
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
                'date',
                'doctrine_orm_date',
                array(
                    'label' => 'backend.admin.spending.date',
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
                'category',
                null,
                array(
                    'label' => 'backend.admin.spending.category',
                ),
                EntityType::class,
                array(
                    'expanded' => false,
                    'multiple' => false,
                    'class' => SpendingCategory::class,
                    'query_builder' => $this->getConfigurationPool()->getContainer()->get('app.spending_category_repository')->getEnabledSortedByNameQB(),
                )
            )
            ->add(
                'provider',
                null,
                array(
                    'label' => 'backend.admin.spending.provider',
                ),
                EntityType::class,
                array(
                    'expanded' => false,
                    'multiple' => false,
                    'class' => Provider::class,
                    'query_builder' => $this->getConfigurationPool()->getContainer()->get('app.provider_repository')->getEnabledSortedByNameQB(),
                )
            )
            ->add(
                'description',
                null,
                array(
                    'label' => 'backend.admin.spending.description',
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
                ),
                null,
                array(
                    'widget' => 'single_text',
                    'format' => 'dd-MM-yyyy',
                )
            )
            ->add(
                'paymentMethod',
                null,
                array(
                    'label' => 'backend.admin.customer.payment_method',
                ),
                ChoiceType::class,
                array(
                    'label' => 'backend.admin.customer.payment_method',
                    'choices' => StudentPaymentEnum::getEnumArray(),
                    'required' => true,
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
                'date',
                null,
                array(
                    'label' => 'backend.admin.spending.date',
                    'format' => 'd/m/Y',
                    'editable' => true,
                )
            )
            ->add(
                'category',
                null,
                array(
                    'label' => 'backend.admin.spending.category',
                    'editable' => false,
                    'associated_property' => 'name',
                    'sortable' => true,
                    'sort_field_mapping' => array('fieldName' => 'name'),
                    'sort_parent_association_mappings' => array(array('fieldName' => 'category')),
                )
            )
            ->add(
                'provider',
                null,
                array(
                    'label' => 'backend.admin.spending.provider',
                    'editable' => false,
                    'associated_property' => 'name',
                    'sortable' => true,
                    'sort_field_mapping' => array('fieldName' => 'name'),
                    'sort_parent_association_mappings' => array(array('fieldName' => 'category')),
                )
            )
            ->add(
                'description',
                null,
                array(
                    'label' => 'backend.admin.spending.description',
                    'editable' => true,
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
                    'label' => 'backend.admin.actions',
                    'actions' => array(
                        'edit' => array('template' => '::Admin/Buttons/list__action_edit_button.html.twig'),
                        'document' => array('template' => '::Admin/Buttons/list__action_spending_document_button.html.twig'),
                        'duplicate' => array('template' => '::Admin/Buttons/list__action_invoice_duplicate_button.html.twig'),
                        'delete' => array('template' => '::Admin/Buttons/list__action_delete_button.html.twig'),
                    ),
                )
            )
        ;
    }

    /**
     * @return array
     */
    public function getExportFields()
    {
        return array(
            'beginString',
            'endString',
            'classroomString',
            'teacher',
            'group',
            'studentsAmount',
            'studentsString',
        );
    }
}
