<?php

namespace AppBundle\Admin;

use AppBundle\Entity\City;
use AppBundle\Entity\Person;
use AppBundle\Enum\StudentPaymentEnum;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Form\Type\AdminType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Sonata\AdminBundle\Route\RouteCollection;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

/**
 * Class PersonAdmin.
 *
 * @category Admin
 */
class PersonAdmin extends AbstractBaseAdmin
{
    protected $classnameLabel = 'Person';
    protected $baseRoutePattern = 'students/parent';
    protected $datagridValues = array(
        '_sort_by' => 'surname',
        '_sort_order' => 'asc',
    );

    /**
     * @var array
     */
    protected $formOptions = array(
        'cascade_validation' => true,
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
            ->with('backend.admin.general', $this->getFormMdSuccessBoxArray(3))
            ->add(
                'name',
                null,
                array(
                    'label' => 'backend.admin.parent.name',
                )
            )
            ->add(
                'surname',
                null,
                array(
                    'label' => 'backend.admin.parent.surname',
                )
            )
            ->end()
            ->with('backend.admin.contact.contact', $this->getFormMdSuccessBoxArray(3))
            ->add(
                'phone',
                null,
                array(
                    'label' => 'backend.admin.parent.phone',
                )
            )
            ->add(
                'email',
                null,
                array(
                    'label' => 'backend.admin.parent.email',
                    'required' => false,
                )
            )
            ->add(
                'address',
                null,
                array(
                    'label' => 'backend.admin.parent.address',
                    'required' => false,
                )
            )
            ->add(
                'city',
                EntityType::class,
                array(
                    'label' => 'backend.admin.parent.city',
                    'required' => true,
                    'class' => City::class,
                    'choice_label' => 'name',
                    'query_builder' => $this->getConfigurationPool()->getContainer()->get('app.city_repository')->getEnabledSortedByNameQB(),
                )
            )
            ->end()
            ->with('backend.admin.student.payment_information', $this->getFormMdSuccessBoxArray(3))
            ->add(
                'payment',
                ChoiceType::class,
                array(
                    'label' => 'backend.admin.student.payment',
                    'choices' => StudentPaymentEnum::getEnumArray(),
                    'multiple' => false,
                    'expanded' => false,
                    'required' => true,
                )
            )
            ->add(
                'bank',
                AdminType::class,
                array(
                    'label' => ' ',
                    'required' => false,
                    'btn_add' => false,
                    'by_reference' => false,
                )
            )
            ->end()
            ->with('backend.admin.controls', $this->getFormMdSuccessBoxArray(3))
            ->add(
                'students',
                null,
                array(
                    'label' => 'backend.admin.parent.students',
                    'required' => false,
                    'disabled' => true,
                )
            )
            ->add(
                'dni',
                null,
                array(
                    'label' => 'backend.admin.parent.dni',
                )
            )
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
                'dni',
                null,
                array(
                    'label' => 'backend.admin.parent.dni',
                )
            )
            ->add(
                'name',
                null,
                array(
                    'label' => 'backend.admin.parent.name',
                )
            )
            ->add(
                'surname',
                null,
                array(
                    'label' => 'backend.admin.parent.surname',
                )
            )
            ->add(
                'phone',
                null,
                array(
                    'label' => 'backend.admin.parent.phone',
                )
            )
            ->add(
                'email',
                null,
                array(
                    'label' => 'backend.admin.parent.email',
                )
            )
            ->add(
                'address',
                null,
                array(
                    'label' => 'backend.admin.parent.address',
                )
            )
            ->add(
                'city',
                null,
                array(
                    'label' => 'backend.admin.parent.city',
                )
            )
            ->add(
                'payment',
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
                'bank.name',
                null,
                array(
                    'label' => 'backend.admin.bank.name',
                )
            )
            ->add(
                'bank.swiftCode',
                null,
                array(
                    'label' => 'backend.admin.bank.swiftCode',
                )
            )
            ->add(
                'bank.accountNumber',
                null,
                array(
                    'label' => 'backend.admin.bank.accountNumber',
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
                'name',
                null,
                array(
                    'label' => 'backend.admin.parent.name',
                    'editable' => true,
                )
            )
            ->add(
                'surname',
                null,
                array(
                    'label' => 'backend.admin.parent.surname',
                    'editable' => true,
                )
            )
            ->add(
                'phone',
                null,
                array(
                    'label' => 'backend.admin.parent.phone',
                    'editable' => true,
                )
            )
            ->add(
                'email',
                null,
                array(
                    'label' => 'backend.admin.parent.email',
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
                        'edit' => array('template' => '::Admin/Buttons/list__action_edit_button.html.twig'),
                    ),
                    'label' => 'Accions',
                )
            )
        ;
    }

    /**
     * @param Person $object
     */
    public function prePersist($object)
    {
        $this->commonPreActions($object);
    }

    /**
     * @param Person $object
     */
    public function preUpdate($object)
    {
        $this->commonPreActions($object);
    }

    /**
     * @param Person $object
     */
    private function commonPreActions($object)
    {
        if ($object->getBank()->getAccountNumber()) {
            $object->getBank()->setAccountNumber(strtoupper($object->getBank()->getAccountNumber()));
        }
        if ($object->getBank()->getSwiftCode()) {
            $object->getBank()->setSwiftCode(strtoupper($object->getBank()->getSwiftCode()));
        }
    }
}
