<?php

namespace AppBundle\Admin;

use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

/**
 * Class CityAdmin.
 *
 * @category Admin
 *
 * @author   Wils Iglesias <wiglesias83@gmail.com>
 */
class CityAdmin extends AbstractBaseAdmin
{
    protected $classnameLabel = 'City';
    protected $baseRoutePattern = 'administrations/city';
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
        $collection->remove('delete');
    }

    /**
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->with('backend.admin.general', $this->getFormMdSuccessBoxArray(6))
            ->add(
                'postalCode',
                null,
                array(
                    'label' => 'backend.admin.city.postalCode',
                )
            )
            ->add(
                'name',
                null,
                array(
                    'label' => 'backend.admin.city.name',
                )
            )
            ->end()
            ->with('backend.admin.controls', $this->getFormMdSuccessBoxArray(3))
            ->add(
                'province',
                EntityType::class,
                array(
                    'label' => 'backend.admin.city.province',
                    'required' => true,
                    'class' => 'AppBundle:Province',
                    'choice_label' => 'name',
                    'query_builder' => $this->getConfigurationPool()->getContainer()->get('app.province_repository')->getEnabledSortedByNameQB(),
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
                'postalCode',
                null,
                array(
                    'label' => 'backend.admin.city.postalCode',
                )
            )
            ->add(
                'name',
                null,
                array(
                    'label' => 'backend.admin.city.name',
                )
            )
            ->add(
                'province',
                null,
                array(
                    'label' => 'backend.admin.city.province',
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
                'postalCode',
                null,
                array(
                    'label' => 'backend.admin.city.postalCode',
                    'editable' => true,
                )
            )
            ->add(
                'name',
                null,
                array(
                    'label' => 'backend.admin.city.name',
                    'editable' => true,
                )
            )
            ->add(
                'province',
                null,
                array(
                    'label' => 'backend.admin.city.province',
                    'editable' => true,
                    'associated_property' => 'name',
                    'sortable' => true,
                    'sort_field_mapping' => array('fieldName' => 'name'),
                    'sort_parent_association_mappings' => array(array('fieldName' => 'province')),
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
                    'label' => 'Accions',
                )
            )
        ;
    }
}
