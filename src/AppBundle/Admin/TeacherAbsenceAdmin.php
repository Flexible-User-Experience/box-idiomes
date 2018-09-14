<?php

namespace AppBundle\Admin;

use AppBundle\Entity\Teacher;
use AppBundle\Enum\TeacherAbsenceTypeEnum;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\CoreBundle\Form\Type\DatePickerType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

/**
 * Class TeacherAbsenceAdmin.
 *
 * @category Admin
 */
class TeacherAbsenceAdmin extends AbstractBaseAdmin
{
    protected $classnameLabel = 'Absence';
    protected $baseRoutePattern = 'teachers/absence';
    protected $datagridValues = array(
        '_sort_by' => 'day',
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
                'day',
                DatePickerType::class,
                array(
                    'label' => 'backend.admin.teacher_absence.day',
                    'format' => 'd/M/y',
                    'required' => true,
                )
            )
            ->add(
                'type',
                ChoiceType::class,
                array(
                    'label' => 'backend.admin.teacher_absence.type',
                    'choices' => TeacherAbsenceTypeEnum::getEnumArray(),
                    'multiple' => false,
                    'expanded' => false,
                    'required' => true,
                )
            )
            ->add(
                'teacher',
                EntityType::class,
                array(
                    'label' => 'backend.admin.teacher_absence.teacher',
                    'required' => true,
                    'class' => Teacher::class,
                    'choice_label' => 'name',
                    'query_builder' => $this->getConfigurationPool()->getContainer()->get('app.teacher_repository')->getEnabledSortedByNameQB(),
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
                'day',
                'doctrine_orm_date',
                array(
                    'label' => 'backend.admin.teacher_absence.day',
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
                'type',
                null,
                array(
                    'label' => 'backend.admin.teacher_absence.type',
                ),
                ChoiceType::class,
                array(
                    'expanded' => false,
                    'multiple' => false,
                    'choices' => TeacherAbsenceTypeEnum::getEnumArray(),
                )
            )
            ->add(
                'teacher',
                null,
                array(
                    'label' => 'backend.admin.teacher_absence.teacher',
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
                'image',
                null,
                array(
                    'label' => 'backend.admin.image',
                    'template' => '::Admin/Cells/list__cell_absence_image_field.html.twig',
                )
            )
            ->add(
                'teacher',
                null,
                array(
                    'label' => 'backend.admin.teacher_absence.teacher',
                    'editable' => false,
                    'associated_property' => 'name',
                    'sortable' => true,
                    'sort_field_mapping' => array('fieldName' => 'name'),
                    'sort_parent_association_mappings' => array(array('fieldName' => 'teacher')),
                )
            )
            ->add(
                'day',
                'date',
                array(
                    'label' => 'backend.admin.teacher_absence.day',
                    'format' => 'd/m/Y',
                    'editable' => true,
                )
            )
            ->add(
                'type',
                null,
                array(
                    'label' => 'backend.admin.teacher_absence.type',
                    'template' => '::Admin/Cells/list__cell_teacher_absence_type.html.twig',
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
            )
        ;
    }

    /**
     * @return array
     */
    public function getExportFields()
    {
        return array(
            'dayString',
            'typeString',
            'teacher',
        );
    }
}
