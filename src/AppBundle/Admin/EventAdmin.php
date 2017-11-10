<?php

namespace AppBundle\Admin;

use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Route\RouteCollection;

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
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        unset($this->listModes['mosaic']);
        $listMapper
            ->add(
                'begin',
                null,
                array(
                    'label' => 'backend.admin.event.begin',
                    'editable' => true,
                )
            )
            ->add(
                'end',
                null,
                array(
                    'label' => 'backend.admin.event.end',
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
                    'editable' => true,
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
                    'label' => 'backend.admin.actions',
                )
            )
        ;
    }
}
