<?php

namespace AppBundle\Admin;

use AppBundle\Enum\StudentPaymentEnum;
use Ivory\CKEditorBundle\Form\Type\CKEditorType;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

/**
 * Class StudentAdmin.
 *
 * @category Admin
 *
 * @author   Wils Iglesias <wiglesias83@gmail.com>
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
        ;
    }

    /**
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->with('backend.admin.student.student', $this->getFormMdSuccessBoxArray(4))
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
                'comments',
                CKEditorType::class,
                array(
                    'label' => 'backend.admin.student.comments',
                    'config_name' => 'my_config',
                    'required' => false,
                )
            )
            ->end()
            ->with('backend.admin.student.parent', $this->getFormMdSuccessBoxArray(3))
            ->add(
                'contactName',
                null,
                array(
                    'label' => 'backend.admin.student.contactName',
                )
            )
            ->add(
                'contactDni',
                null,
                array(
                    'label' => 'backend.admin.student.contactDni',
                )
            )
            ->add(
                'contactPhone',
                null,
                array(
                    'label' => 'backend.admin.student.contactPhone',
                )
            )
            ->add(
                'ownMobile',
                null,
                array(
                    'label' => 'backend.admin.student.ownMobile',
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
                null,
                array(
                    'label' => 'backend.admin.student.city',
                    'required' => false,
                )
            )
            ->add(
                'showSEPAequalAddress',
                CheckboxType::class,
                array(
                    'label' => 'backend.admin.student.showSEPAequalAddress',
                    'required' => false,
                )
            )
            ->add(
                'parentAddress',
                null,
                array(
                    'label' => 'backend.admin.student.parentAddress',
                )
            )
            ->end()
            ->with('backend.admin.student.payment_information', $this->getFormMdSuccessBoxArray(3))
            ->add(
                'bankAccountName',
                null,
                array(
                    'label' => 'backend.admin.student.bankAccountName',
                    'required' => false,
                )
            )
            ->add(
                'bankAccountNumber',
                null,
                array(
                    'label' => 'backend.admin.student.bankAccountNumber',
                    'required' => false,
                )
            )
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
            ->end()
            ->with('backend.admin.controls', $this->getFormMdSuccessBoxArray(2))
            ->add(
                'birthDate',
                'sonata_type_date_picker',
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
                null,
                array(
                    'label' => 'backend.admin.student.birthDate',
                )
            )
            ->add(
                'ownMobile',
                null,
                array(
                    'label' => 'backend.admin.student.ownMobile',
                )
            )
            ->add(
                'contactPhone',
                null,
                array(
                    'label' => 'backend.admin.student.contactPhone',
                )
            )
            ->add(
                'contactName',
                null,
                array(
                    'label' => 'backend.admin.student.contactName',
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
                'ownMobile',
                null,
                array(
                    'label' => 'backend.admin.student.ownMobile',
                    'editable' => true,
                )
            )
            ->add(
                'contactPhone',
                null,
                array(
                    'label' => 'backend.admin.student.contactPhone',
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
                        'delete' => array('template' => '::Admin/Buttons/list__action_delete_button.html.twig'),
                    ),
                    'label' => 'Accions',
                )
            )
        ;
    }
}
