<?php

namespace AppBundle\Admin;

use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Route\RouteCollection;

/**
 * Class TeacherAbsenceAdmin.
 *
 * @category Admin
 *
 * @author   Wils Iglesias <wiglesias83@gmail.com>
 */
class TeacherAbsenceAdmin extends AbstractBaseAdmin
{
    protected $classnameLabel = 'Absence';
    protected $baseRoutePattern = 'teacher/absence';
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
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add(
                'teacher',
                null,
                array(
                    'label' => 'backend.admin.teacher_absence.teacher',
                )
            )
            ->add(
                'day',
                'doctrine_orm_date',
                array(
                    'label' => 'backend.admin.teacher_absence.day',
                    'field_type' => 'sonata_type_date_picker',
                )
            )
            ->add(
                'enabled',
                null,
                array(
                    'label' => 'backend.admin.enabled',
                )
            );
    }

    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        unset($this->listModes['mosaic']);
        $listMapper
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
}
