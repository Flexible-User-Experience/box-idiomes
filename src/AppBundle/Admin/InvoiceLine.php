<?php

namespace AppBundle\Admin;

/**
 * Class InvoiceLine
 *
 * @category Admin
 * @package  AppBundle\Admin
 * @author   Wils Iglesias <wiglesias83@gmail.com>
 */
class InvoiceLine extends AbstractBaseAdmin
{
    protected $classnameLabel = 'Invoice';
    protected $baseRoutePattern = 'billings/invoice-line';
    protected $datagridValues = array(
        '_sort_by' => 'description',
        '_sort_order' => 'asc',
    );

}
