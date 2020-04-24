<?php

namespace AppBundle\Admin;

use Fenrizbes\ColorPickerTypeBundle\Form\Type\ColorPickerType;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

/**
 * Class ClassGroupAdmin.
 *
 * @category Admin
 */
class ClassGroupAdmin extends AbstractBaseAdmin
{
    protected $classnameLabel = 'Grups';
    protected $baseRoutePattern = 'classrooms/group';
    protected $datagridValues = array(
        '_sort_by' => 'code',
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
            ->add('emails', $this->getRouterIdParameter().'/get-group-emails')
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
                'code',
                null,
                array(
                    'label' => 'backend.admin.class_group.code',
                    'required' => true,
                )
            )
            ->add(
                'name',
                null,
                array(
                    'label' => 'backend.admin.class_group.name',
                    'required' => false,
                )
            )
            ->add(
                'book',
                null,
                array(
                    'label' => 'backend.admin.class_group.book',
                    'required' => false,
                )
            )
            ->end()
            ->with('backend.admin.controls', $this->getFormMdSuccessBoxArray(3))
            ->add(
                'color',
                ColorPickerType::class,
                array(
                    'label' => 'backend.admin.teacher.color',
                    'required' => true,
                    'picker_options' => array(
                        'color' => false,
                        'mode' => 'hsl',
                        'hide' => false,
                        'border' => true,
                        'target' => false,
                        'width' => 200,
                        'palettes' => true,
                        'controls' => array(
                            'horiz' => 's',
                            'vert' => 'l',
                            'strip' => 'h',
                        ),
                    ),
                )
            )
            ->add(
                'isForPrivateLessons',
                CheckboxType::class,
                array(
                    'label' => 'backend.admin.is_for_private_lessons',
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
            ->end();
    }

    /**
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add(
                'code',
                null,
                array(
                    'label' => 'backend.admin.class_group.code',
                )
            )
            ->add(
                'name',
                null,
                array(
                    'label' => 'backend.admin.class_group.name',
                )
            )
            ->add(
                'book',
                null,
                array(
                    'label' => 'backend.admin.class_group.book',
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
                'code',
                null,
                array(
                    'label' => 'backend.admin.class_group.code',
                    'editable' => true,
                )
            )
            ->add(
                'name',
                null,
                array(
                    'label' => 'backend.admin.class_group.name',
                    'editable' => true,
                )
            )
            ->add(
                'book',
                null,
                array(
                    'label' => 'backend.admin.class_group.book',
                    'editable' => true,
                )
            )
            ->add(
                'color',
                null,
                array(
                    'label' => 'backend.admin.class_group.color',
                    'template' => '::Admin/Cells/list__cell_class_group_color.html.twig',
                )
            )
            ->add(
                'isForPrivateLessons',
                null,
                array(
                    'label' => 'backend.admin.is_for_private_lessons',
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
                        'emails' => array('template' => '::Admin/Cells/list__action_group_emails.html.twig'),
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
            'code',
            'name',
            'book',
            'color',
            'isForPrivateLessons',
            'enabled',
        );
    }
}
