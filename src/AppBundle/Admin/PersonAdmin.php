<?php

namespace AppBundle\Admin;

use Sonata\AdminBundle\Route\RouteCollection;

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
}
