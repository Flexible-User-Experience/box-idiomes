<?php

namespace AppBundle\Service;

use AppBundle\Repository\InvoiceRepository;
use SaadTazi\GChartBundle\DataTable\DataTable;
use SaadTazi\GChartBundle\DataTable\DataColumn;

/**
 * Class HighchartFactoryService.
 *
 * @category Service
 */
class ChartsFactoryService
{
    /**
     * @var InvoiceRepository
     */
    private $ir;

    /**
     * Methods.
     */

    /**
     * HighchartFactoryService constructor.
     *
     * @param InvoiceRepository $ir
     */
    public function __construct(InvoiceRepository $ir)
    {
        $this->ir = $ir;
    }

    /**
     * @return DataTable
     */
    public function buildInvoicesDataTableChart()
    {
        // TODO get values from Invoice repository
        $dt = new DataTable();
        $dt->addColumn('id1', 'label 1', 'string');
        $dt->addColumnObject(new DataColumn('id2', 'label 2', 'number'));
        $dt->addColumnObject(new DataColumn('id3', 'label 3', 'number'));

        //test cells as array
        $dt->addRow(array(
            array('v' => 'row 1'),
            array('v' => 2, 'f' => '2 trucks'),
            array('v' => 4, 'f' => '4 bikes'),
        ));
        //simple cell (not an array)
        $dt->addRow(array('row 2', 5, 1));
        //mixed
        $dt->addRow(array('row 3', array('v' => 5), 10));
        $dt->addRow(array('row 4', array('v' => 2), 0));
        $dt->addRow(array('row 5', array('v' => 0), 10));
        $dt->addRow(array('row 5', 10, 0));
        $dt->addRow(array('row 5', 4, 5));

        return $dt;
    }
}
