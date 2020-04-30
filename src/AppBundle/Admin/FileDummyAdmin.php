<?php

namespace AppBundle\Admin;

use Sonata\AdminBundle\Route\RouteCollection;

/**
 * Class BankAdmin.
 *
 * @category Admin
 */
class FileDummyAdmin extends AbstractBaseAdmin
{
    protected $classnameLabel = 'File';
    protected $baseRoutePattern = 'fitxers';
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
        $collection
            ->add('handler', 'gestor')
            ->remove('list')
            ->remove('create')
            ->remove('edit')
            ->remove('show')
            ->remove('delete')
            ->remove('batch')
            ->remove('export')
        ;
    }
}
