<?php

namespace AppBundle\Admin;

use AppBundle\Enum\TeacherColorEnum;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

/**
 * Class TeacherAdmin
 *
 * @category Admin
 * @package  AppBundle\Admin
 * @author   Anton Serra <aserratorta@gmail.com>
 */
class TeacherAdmin extends AbstractBaseAdmin
{
    protected $classnameLabel = 'Teacher';
    protected $baseRoutePattern = 'teachers/teacher';
    protected $datagridValues = array(
        '_sort_by'    => 'position',
        '_sort_order' => 'asc',
    );

    /**
     * Configure route collection
     *
     * @param RouteCollection $collection
     */
    protected function configureRoutes(RouteCollection $collection)
    {
        $collection
            ->remove('batch');
    }

    /**
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->with('backend.admin.general', $this->getFormMdSuccessBoxArray(6))
            ->add(
                'imageFile',
                'file',
                array(
                    'label'    => 'backend.admin.image',
                    'help'     => $this->getImageHelperFormMapperWithThumbnailAspectRatio(),
                    'required' => false,
                )
            )
            ->add(
                'name',
                null,
                array(
                    'label' => 'backend.admin.teacher.name',
                )
            )
            ->add(
                'description',
                TextareaType::class,
                array(
                    'label' => 'backend.admin.description',
                    'attr'     => array(
                        'rows' => 10,
                    )
                )
            )
            ->end()
            ->with('backend.admin.controls', $this->getFormMdSuccessBoxArray(6))
            ->add(
                'position',
                null,
                array(
                    'label' => 'backend.admin.position',
                )
            )
            ->add(
                'color',
                'choice',
                array(
                    'label' => 'backend.admin.teacher.color',
                    'choices'  => TeacherColorEnum::getEnumArray(),
                    'multiple' => false,
                    'expanded' => false,
                    'required' => true,
                )
            )
            ->add(
                'enabled',
                'checkbox',
                array(
                    'label'    => 'backend.admin.enabled',
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
                'name',
                null,
                array(
                    'label' => 'backend.admin.teacher.name',
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
                'position',
                'decimal',
                array(
                    'label'    => 'backend.admin.position',
                    'editable' => false,
                )
            )
            ->add(
                'image',
                null,
                array(
                    'label'    => 'backend.admin.image',
                    'template' => '::Admin/Cells/list__cell_image_field.html.twig'
                )
            )
            ->add(
                'name',
                null,
                array(
                    'label'    => 'backend.admin.teacher.name',
                    'editable' => true,
                )
            )
            ->add(
                'color',
                null,
                array(
                    'label'    => 'backend.admin.teacher.color',
                    'template' => '::Admin/Cells/list__cell_teacher_color.html.twig'
                )
            )
            ->add(
                'enabled',
                null,
                array(
                    'label'    => 'backend.admin.enabled',
                    'editable' => true,
                )
            )
            ->add(
                '_action',
                'actions',
                array(
                    'actions' => array(
                        'edit'   => array('template' => '::Admin/Buttons/list__action_edit_button.html.twig'),
                        'delete' => array('template' => '::Admin/Buttons/list__action_delete_button.html.twig'),
                    ),
                    'label'   => 'backend.admin.actions',
                )
            );
    }
}