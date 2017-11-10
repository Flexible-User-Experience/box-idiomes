<?php

namespace AppBundle\Admin;

use Sonata\AdminBundle\Route\RouteCollection;

/**
 * Class TariffAdmin.
 *
 * @category Admin
 *
 * @author   Wils Iglesias <wiglesias83@gmail.com>
 */
class TariffAdmin extends AbstractBaseAdmin
{
    protected $classnameLabel = 'Tarriff';
    protected $baseRoutePattern = 'classrooms/tariff';
    protected $datagridValues = array(
        '_sort_by' => 'surname',
        '_sort_order' => 'asc',
    );

    /**
     * @var array
     */
    protected $formOptions = array(
        'cascade_validation' => true,
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
}
