<?php

namespace AppBundle\Admin;

use AppBundle\Enum\StudentPaymentEnum;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

/**
 * Class ProviderAdmin.
 *
 * @category Admin
 */
class ProviderAdmin extends AbstractBaseAdmin
{
    protected $classnameLabel = 'Provider';
    protected $baseRoutePattern = 'purchases/provider';
    protected $datagridValues = array(
        '_sort_by' => 'name',
        '_sort_order' => 'asc',
    );

    /**
     * Configure route collection.
     *
     * @param RouteCollection $collection
     */
    protected function configureRoutes(RouteCollection $collection)
    {
        parent::configureRoutes($collection);
        $collection->remove('delete');
    }

    /**
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->with('backend.admin.general', $this->getFormMdSuccessBoxArray(5))
            ->add(
                'tic',
                null,
                array(
                    'label' => 'backend.admin.customer.tic',
                    'required' => true,
                )
            )
            ->add(
                'name',
                null,
                array(
                    'label' => 'backend.admin.customer.name',
                    'required' => true,
                )
            )
            ->add(
                'alias',
                null,
                array(
                    'label' => 'backend.admin.customer.alias',
                )
            )
            ->add(
                'address',
                null,
                array(
                    'label' => 'backend.admin.customer.address',
                )
            )
            ->add(
                'city',
                null,
                array(
                    'label' => 'backend.admin.customer.city',
                    'query_builder' => $this->getConfigurationPool()->getContainer()->get('app.city_repository')->getEnabledSortedByNameQB(),
                    'required' => true,
                )
            )
            ->add(
                'phone',
                null,
                array(
                    'label' => 'backend.admin.customer.phone',
                )
            )
            ->add(
                'email',
                null,
                array(
                    'label' => 'backend.admin.customer.email',
                )
            )
            ->end()
            ->with('backend.admin.payments', $this->getFormMdSuccessBoxArray(4))
            ->add(
                'paymentMethod',
                ChoiceType::class,
                array(
                    'label' => 'backend.admin.customer.payment_method',
                    'choices' => StudentPaymentEnum::getEnumArray(),
                    'required' => true,
                )
            )
            ->add(
                'ibanForBankDraftPayment',
                TextType::class,
                array(
                    'label' => 'backend.admin.customer.iban_for_bank_draft_payment',
                    'required' => false,
                )
            )
            ->end()
            ->with('backend.admin.controls', $this->getFormMdSuccessBoxArray(3))
            ->add(
                'enabled',
                CheckboxType::class,
                array(
                    'label' => 'backend.admin.enabled',
                    'required' => false,
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
                'tic',
                null,
                array(
                    'label' => 'backend.admin.customer.tic',
                )
            )
            ->add(
                'name',
                null,
                array(
                    'label' => 'backend.admin.customer.name',
                )
            )
            ->add(
                'alias',
                null,
                array(
                    'label' => 'backend.admin.customer.alias',
                )
            )
            ->add(
                'address',
                null,
                array(
                    'label' => 'backend.admin.customer.address',
                )
            )
            ->add(
                'city',
                null,
                array(
                    'label' => 'backend.admin.customer.city',
                )
            )
            ->add(
                'phone',
                null,
                array(
                    'label' => 'backend.admin.customer.phone',
                )
            )
            ->add(
                'email',
                null,
                array(
                    'label' => 'backend.admin.customer.email',
                )
            )
            ->add(
                'paymentMethod',
                null,
                array(
                    'label' => 'backend.admin.customer.payment_method',
                )
            )
            ->add(
                'ibanForBankDraftPayment',
                null,
                array(
                    'label' => 'backend.admin.customer.iban_for_bank_draft_payment',
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
                'tic',
                null,
                array(
                    'label' => 'backend.admin.customer.tic',
                    'editable' => true,
                )
            )
            ->add(
                'name',
                null,
                array(
                    'label' => 'backend.admin.customer.name',
                    'editable' => true,
                )
            )
            ->add(
                'alias',
                null,
                array(
                    'label' => 'backend.admin.customer.alias',
                    'editable' => true,
                )
            )
            ->add(
                'city',
                null,
                array(
                    'label' => 'backend.admin.customer.city',
                    'editable' => true,
                )
            )
            ->add(
                'email',
                null,
                array(
                    'label' => 'backend.admin.customer.email',
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
                    'label' => 'backend.admin.actions',
                    'actions' => array(
                        'edit' => array('template' => '::Admin/Buttons/list__action_edit_button.html.twig'),
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
