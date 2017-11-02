<?php

namespace AppBundle\Admin;

use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

/**
 * Class PersonAdmin.
 *
 * @category Admin
 *
 * @author   Wils Iglesias <wiglesias83@gmail.com>
 */
class PersonAdmin extends AbstractBaseAdmin
{
    protected $classnameLabel = 'Person';
    protected $baseRoutePattern = 'administration/person';
    protected $datagridValues = array(
        '_sort_by' => 'name',
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
    }

    /**
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->with('backend.admin.general', $this->getFormMdSuccessBoxArray(4))
            ->add(
                'dni',
                null,
                array(
                    'label' => 'backend.admin.parent.dni',
                )
            )
            ->add(
                'name',
                null,
                array(
                    'label' => 'backend.admin.parent.name',
                )
            )
            ->add(
                'surname',
                null,
                array(
                    'label' => 'backend.admin.parent.surname',
                )
            )
            ->end()
            ->with('backend.admin.contact.contact', $this->getFormMdSuccessBoxArray(4))
            ->add(
                'phone',
                null,
                array(
                    'label' => 'backend.admin.parent.phone',
                )
            )
            ->add(
                'email',
                null,
                array(
                    'label' => 'backend.admin.parent.email',
                    'required' => false,
                )
            )
            ->add(
                'address',
                null,
                array(
                    'label' => 'backend.admin.parent.address',
                    'required' => false,
                )
            )
            ->add(
                'city',
                null,
                array(
                    'label' => 'backend.admin.parent.city',
                    'required' => false,
                )
            )
            ->end()
            ->with('backend.admin.controls', $this->getFormMdSuccessBoxArray(3))
            ->add(
                'bank',
                EntityType::class,
                array(
                    'label' => 'backend.admin.parent.bank',
                    'class' => 'AppBundle:Bank',
                    'required' => false,
                    'multiple' => false,
//                    'query_builder' => $this->rm->getUserRepository()->getEnabledSortedByNameQB(),
                    'by_reference' => false,
                    'add_button' => true,
                )
            )
            ->add(
                'students',
                null,
                array(
                    'label' => 'backend.admin.parent.students',
                    'required' => false,
                    'disabled' => true,
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
                    'label' => 'backend.admin.parent.name',
                )
            )
            ->add(
                'surname',
                null,
                array(
                    'label' => 'backend.admin.parent.surname',
                )
            )
            ->add(
                'phone',
                null,
                array(
                    'label' => 'backend.admin.parent.phone',
                )
            )
            ->add(
                'email',
                null,
                array(
                    'label' => 'backend.admin.parent.email',
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
                    'label' => 'backend.admin.parent.name',
                    'editable' => true,
                )
            )
            ->add(
                'surname',
                null,
                array(
                    'label' => 'backend.admin.parent.surname',
                    'editable' => true,
                )
            )
            ->add(
                'phone',
                null,
                array(
                    'label' => 'backend.admin.parent.phone',
                    'editable' => true,
                )
            )
            ->add(
                'email',
                null,
                array(
                    'label' => 'backend.admin.parent.email',
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
                    'label' => 'Accions',
                )
            )
        ;
    }
}
