<?php

namespace AppBundle\Admin;

use Sonata\AdminBundle\Datagrid\ListMapper;

/**
 * Class StudentAdmin
 *
 * @category Admin
 * @author   Wils Iglesias <wiglesias83@gmail.com>
 */
class StudentAdmin extends AbstractBaseAdmin
{
    protected $classnameLabel = 'Student';
    protected $baseRoutePattern = 'students/student';
    protected $datagridValues = array(
        '_sort_by'    => 'surname',
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
                'name',
                null,
                array(
                    'label' => 'Nom',
                    'editable' => true,
                )
            )
            ->add(
                'surname',
                null,
                array(
                    'label' => 'Cognoms',
                    'editable' => true,
                )
            )
            ->add(
                'enabled',
                null,
                array(
                    'label' => 'Actiu',
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
                    'label' => 'Accions',
                )
            )
        ;
    }
}
