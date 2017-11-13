<?php

namespace AppBundle\Admin;

use AppBundle\Enum\EventClassroomTypeEnum;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\CoreBundle\Form\Type\DateTimePickerType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

/**
 * Class EventAdmin.
 *
 * @category Admin
 *
 * @author   Wils Iglesias <wiglesias83@gmail.com>
 */
class EventAdmin extends AbstractBaseAdmin
{
    protected $classnameLabel = 'Event';
    protected $baseRoutePattern = 'classrooms/event';
    protected $datagridValues = array(
        '_sort_by' => 'begin',
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
        $collection
            ->remove('delete')
        ;
    }

    /**
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->with('backend.admin.general', $this->getFormMdSuccessBoxArray(4))
            ->add(
                'begin',
                DateTimePickerType::class,
                array(
                    'label' => 'backend.admin.event.begin',
                    'format' => 'd/M/y H:m',
                    'required' => true,
                )
            )
            ->add(
                'end',
                DateTimePickerType::class,
                array(
                    'label' => 'backend.admin.event.end',
                    'format' => 'd/M/y H:m',
                    'required' => true,
                )
            )
            ->add(
                'dayFrequencyRepeat',
                null,
                array(
                    'label' => 'backend.admin.event.dayFrequencyRepeat',
                    'required' => true,
                )
            )
            ->add(
                'until',
                DateTimePickerType::class,
                array(
                    'label' => 'backend.admin.event.until',
                    'format' => 'd/M/y H:m',
                    'required' => true,
                )
            )
            ->end()
            ->with('backend.admin.controls', $this->getFormMdSuccessBoxArray(3))
            ->add(
                'teacher',
                null,
                array(
                    'label' => 'backend.admin.event.teacher',
                    'required' => true,
                )
            )
            ->add(
                'group',
                null,
                array(
                    'label' => 'backend.admin.event.group',
                    'required' => true,
                )
            )
            ->add(
                'students',
                null,
                array(
                    'label' => 'backend.admin.event.students',
                    'required' => false,
                )
            )
            ->add(
                'classroom',
                ChoiceType::class,
                array(
                    'label' => 'backend.admin.event.classroom',
                    'choices' => EventClassroomTypeEnum::getEnumArray(),
                    'multiple' => false,
                    'expanded' => false,
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
                'begin',
                'doctrine_orm_date',
                array(
                    'label' => 'backend.admin.event.begin',
                    'field_type' => 'sonata_type_datetime_picker',
                )
            )
            ->add(
                'end',
                'doctrine_orm_date',
                array(
                    'label' => 'backend.admin.event.end',
                    'field_type' => 'sonata_type_datetime_picker',
                )
            )
            ->add(
                'classroom',
                null,
                array(
                    'label' => 'backend.admin.event.classroom',
                ),
                ChoiceType::class,
                array(
                    'expanded' => false,
                    'multiple' => false,
                    'choices' => EventClassroomTypeEnum::getEnumArray(),
                )
            )
            ->add(
                'group',
                null,
                array(
                    'label' => 'backend.admin.event.group',
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
                'begin',
                'date',
                array(
                    'label' => 'backend.admin.event.begin',
                    'format' => 'd/m/Y H:i',
                    'editable' => true,
                )
            )
            ->add(
                'end',
                'date',
                array(
                    'label' => 'backend.admin.event.end',
                    'format' => 'd/m/Y H:i',
                    'editable' => true,
                )
            )
            ->add(
                'teacher',
                null,
                array(
                    'label' => 'backend.admin.event.teacher',
                )
            )
            ->add(
                'classroom',
                null,
                array(
                    'label' => 'backend.admin.event.classroom',
                    'template' => '::Admin/Cells/list__cell_classroom_type.html.twig',
                )
            )
            ->add(
                'group',
                null,
                array(
                    'label' => 'backend.admin.event.group',
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
                    'label' => 'backend.admin.actions',
                )
            )
        ;
    }
}
