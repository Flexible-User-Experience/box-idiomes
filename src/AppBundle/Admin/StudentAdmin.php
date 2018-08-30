<?php

namespace AppBundle\Admin;

use AppBundle\Entity\City;
use AppBundle\Entity\Person;
use AppBundle\Entity\Tariff;
use AppBundle\Enum\StudentPaymentEnum;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Form\Type\AdminType;
use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\CoreBundle\Form\Type\DatePickerType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

/**
 * Class StudentAdmin.
 *
 * @category Admin
 */
class StudentAdmin extends AbstractBaseAdmin
{
    protected $classnameLabel = 'Student';
    protected $baseRoutePattern = 'students/student';
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
        $collection
            ->add('imagerights', $this->getRouterIdParameter().'/image-rights')
            ->add('sepaagreement', $this->getRouterIdParameter().'/sepa-agreement')
            ->remove('delete')
        ;
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
                    'label' => 'backend.admin.student.name',
                )
            )
            ->add(
                'surname',
                null,
                array(
                    'label' => 'backend.admin.student.surname',
                )
            )
            ->add(
                'parent',
                EntityType::class,
                array(
                    'label' => 'backend.admin.student.parent',
                    'required' => false,
                    'class' => Person::class,
                    'choice_label' => 'fullcanonicalname',
                    'query_builder' => $this->getConfigurationPool()->getContainer()->get('app.parent_repository')->getEnabledSortedBySurnameQB(),
                )
            )
            ->add(
                'comments',
                TextareaType::class,
                array(
                    'label' => 'backend.admin.student.comments',
                    'required' => false,
                    'attr' => array(
                        'rows' => 8,
                        'style' => 'resize:vertical',
                    ),
                )
            )
            ->end()
            ->with('backend.admin.contact.contact', $this->getFormMdSuccessBoxArray(3))
            ->add(
                'phone',
                null,
                array(
                    'label' => 'backend.admin.student.phone',
                )
            )
            ->add(
                'email',
                null,
                array(
                    'label' => 'backend.admin.student.email',
                    'required' => false,
                )
            )
            ->add(
                'address',
                null,
                array(
                    'label' => 'backend.admin.student.address',
                    'required' => false,
                )
            )
            ->add(
                'city',
                EntityType::class,
                array(
                    'label' => 'backend.admin.student.city',
                    'required' => true,
                    'class' => City::class,
                    'choice_label' => 'name',
                    'query_builder' => $this->getConfigurationPool()->getContainer()->get('app.city_repository')->getEnabledSortedByNameQB(),
                )
            )
            ->end();
        if (!$this->getSubject()->getParent()) {
            $formMapper
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
                ->end();
        }
        $formMapper
            ->with('backend.admin.controls', $this->getFormMdSuccessBoxArray(3))
            ->add(
                'dni',
                null,
                array(
                    'label' => 'backend.admin.student.dni',
                    'required' => false,
                )
            )
            ->add(
                'birthDate',
                DatePickerType::class,
                array(
                    'label' => 'backend.admin.student.birthDate',
                    'format' => 'd/M/y',
                )
            )
            ->add(
                'schedule',
                null,
                array(
                    'label' => 'backend.admin.student.schedule',
                )
            )
            ->add(
                'tariff',
                EntityType::class,
                array(
                    'label' => 'backend.admin.student.tariff',
                    'required' => true,
                    'class' => Tariff::class,
                    'query_builder' => $this->getConfigurationPool()->getContainer()->get('app.tariff_repository')->findAllSortedByYearAndPriceQB(),
                )
            )
            ->add(
                'hasImageRightsAccepted',
                CheckboxType::class,
                array(
                    'label' => 'backend.admin.imagerigths.checkbox_label',
                    'required' => false,
                )
            )
            ->add(
                'hasSepaAgreementAccepted',
                CheckboxType::class,
                array(
                    'label' => 'backend.admin.sepaagreement.checkbox_label',
                    'required' => false,
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
                'name',
                null,
                array(
                    'label' => 'backend.admin.student.name',
                )
            )
            ->add(
                'surname',
                null,
                array(
                    'label' => 'backend.admin.student.surname',
                )
            )
            ->add(
                'birthDate',
                'doctrine_orm_date',
                array(
                    'label' => 'backend.admin.student.birthDate',
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
                'phone',
                null,
                array(
                    'label' => 'backend.admin.student.phone',
                )
            )
            ->add(
                'email',
                null,
                array(
                    'label' => 'backend.admin.student.email',
                )
            )
            ->add(
                'parent',
                null,
                array(
                    'label' => 'backend.admin.student.parent',
                )
            )
            ->add(
                'dni',
                null,
                array(
                    'label' => 'backend.admin.student.dni',
                )
            )
            ->add(
                'address',
                null,
                array(
                    'label' => 'backend.admin.student.address',
                )
            )
            ->add(
                'city',
                null,
                array(
                    'label' => 'backend.admin.student.city',
                )
            )
            ->add(
                'comments',
                null,
                array(
                    'label' => 'backend.admin.student.comments',
                )
            )
            ->add(
                'tariff',
                null,
                array(
                    'label' => 'backend.admin.student.tariff',
                )
            )
            ->add(
                'schedule',
                null,
                array(
                    'label' => 'backend.admin.student.schedule',
                )
            )
            ->add(
                'hasImageRightsAccepted',
                null,
                array(
                    'label' => 'backend.admin.imagerigths.checkbox_label',
                )
            )
            ->add(
                'hasSepaAgreementAccepted',
                null,
                array(
                    'label' => 'backend.admin.sepaagreement.checkbox_label',
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
                    'label' => 'backend.admin.student.name',
                    'editable' => true,
                )
            )
            ->add(
                'surname',
                null,
                array(
                    'label' => 'backend.admin.student.surname',
                    'editable' => true,
                )
            )
            ->add(
                'phone',
                null,
                array(
                    'label' => 'backend.admin.student.phone',
                    'editable' => true,
                )
            )
            ->add(
                'email',
                null,
                array(
                    'label' => 'backend.admin.student.email',
                    'editable' => true,
                )
            )
            ->add(
                'hasImageRightsAccepted',
                null,
                array(
                    'label' => 'backend.admin.imagerigths.checkbox_label',
                    'editable' => true,
                )
            )
            ->add(
                'hasSepaAgreementAccepted',
                null,
                array(
                    'label' => 'backend.admin.sepaagreement.checkbox_label',
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
                        'imagerights' => array(
                            'template' => '::Admin/Cells/list__action_image_rights.html.twig',
                        ),
                        'sepaagreement' => array(
                            'template' => '::Admin/Cells/list__action_sepa_agreement.html.twig',
                        ),
                    ),
                    'label' => 'backend.admin.actions',
                )
            )
        ;
    }
}
