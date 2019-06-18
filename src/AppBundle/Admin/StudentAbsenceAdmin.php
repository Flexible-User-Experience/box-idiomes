<?php

namespace AppBundle\Admin;

use AppBundle\Entity\Student;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\CoreBundle\Form\Type\DatePickerType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

/**
 * Class StudentAbsenceAdmin.
 *
 * @category Admin
 */
class StudentAbsenceAdmin extends AbstractBaseAdmin
{
    protected $classnameLabel = 'Absence';
    protected $baseRoutePattern = 'students/absence';
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
                'student',
                EntityType::class,
                array(
                    'label' => 'backend.admin.student.student',
                    'required' => true,
                    'class' => Student::class,
                    'choice_label' => 'getFullCanonicalName',
                    'query_builder' => $this->getConfigurationPool()->getContainer()->get('app.student_repository')->getAllSortedBySurnameQB(),
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
                'student',
                null,
                array(
                    'label' => 'backend.admin.teacher_absence.teacher',
                )
            )
            ->add(
                'notificationDate',
                null,
                array(
                    'label' => 'backend.admin.student.notification_date',
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
                'hasBeenNotified',
                null,
                array(
                    'label' => 'backend.admin.student.has_been_notified',
                    'editable' => false,
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
                'student',
                null,
                array(
                    'label' => 'backend.admin.student.student',
                    'editable' => false,
                    'associated_property' => 'name',
                    'sortable' => true,
                    'sort_field_mapping' => array('fieldName' => 'name'),
                    'sort_parent_association_mappings' => array(array('fieldName' => 'student')),
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
                'notificationDate',
                'date',
                array(
                    'label' => 'backend.admin.student.notification_date',
                    'format' => 'd/m/Y H:i',
                    'editable' => false,
                )
            )
            ->add(
                'hasBeenNotified',
                null,
                array(
                    'label' => 'backend.admin.student.has_been_notified',
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
            'student',
        );
    }
}
