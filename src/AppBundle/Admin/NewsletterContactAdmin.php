<?php

namespace AppBundle\Admin;

use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\Form\Type\DateTimePickerType;

/**
 * Class NewsletterContactAdmin.
 *
 * @category Admin
 */
class NewsletterContactAdmin extends AbstractBaseAdmin
{
    protected $classnameLabel = 'Newsletter Contact';
    protected $baseRoutePattern = 'contacts/newsletter';
    protected $datagridValues = array(
        '_sort_by' => 'createdAt',
        '_sort_order' => 'desc',
    );

    /**
     * Configure route collection.
     *
     * @param RouteCollection $collection
     */
    protected function configureRoutes(RouteCollection $collection)
    {
        $collection
            ->remove('create')
            ->remove('edit')
            ->remove('delete')
            ->remove('batch')
            ->add('answer', $this->getRouterIdParameter().'/answer')
            ->remove('batch');
    }

    /**
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add(
                'createdAt',
                'doctrine_orm_datetime',
                array(
                    'label' => 'backend.admin.date',
                    'field_type' => DateTimePickerType::class,
                    'format' => 'd-m-Y H:i',
                ),
                null,
                array(
                    'widget' => 'single_text',
                    'format' => 'dd-MM-yyyy HH:mm',
                )
            )
            ->add(
                'email',
                null,
                array(
                    'label' => 'backend.admin.contact.email',
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
                'createdAt',
                'date',
                array(
                    'label' => 'backend.admin.contact.date',
                    'format' => 'd/m/Y H:i',
                )
            )
            ->add(
                'email',
                null,
                array(
                    'label' => 'backend.admin.contact.email',
                )
            )
        ;
    }
}
