<?php

namespace AppBundle\Admin;

use Sonata\AdminBundle\Datagrid\ListMapper;

/**
 * Class ClassGroupAdmin.
 *
 * @category Admin
 *
 * @author   Wils Iglesias <wiglesias83@gmail.com>
 */
class ClassGroupAdmin extends AbstractBaseAdmin
{
    protected $classnameLabel = 'Class Group';
    protected $baseRoutePattern = 'class/class-group';
    protected $datagridValues = array(
        '_sort_by' => 'code',
        '_sort_order' => 'asc',
    );

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
                        'delete' => array('template' => '::Admin/Buttons/list__action_delete_button.html.twig'),
                    ),
                    'label' => 'backend.admin.actions',
                )
            )
        ;
    }
}
