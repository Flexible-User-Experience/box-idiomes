<?php

namespace AppBundle\Service;

use AppBundle\Repository\InvoiceRepository;
use AppBundle\Repository\ReceiptRepository;
use SaadTazi\GChartBundle\DataTable\DataRow;
use SaadTazi\GChartBundle\DataTable\DataCell;
use SaadTazi\GChartBundle\DataTable\DataTable;
use SaadTazi\GChartBundle\DataTable\DataColumn;

/**
 * Class ChartsFactoryService.
 *
 * @category Service
 */
class ChartsFactoryService
{
    /**
     * @var ReceiptRepository
     */
    private $rr;

    /**
     * @var InvoiceRepository
     */
    private $ir;

    /**
     * Methods.
     */

    /**
     * ChartsFactoryService constructor.
     *
     * @param ReceiptRepository $rr
     * @param InvoiceRepository $ir
     */
    public function __construct(ReceiptRepository $rr, InvoiceRepository $ir)
    {
        $this->rr = $rr;
        $this->ir = $ir;
    }

    /**
     * @return DataTable
     */
    public function buildLastYearIncomingsChart()
    {
        // TODO get incoming values from Receipts and Invoices repositories
        $dt = new DataTable();
        $dt->addColumnObject(new DataColumn('id1', 'title', 'string'));
        $dt->addColumnObject(new DataColumn('id2', 'incomings', 'number'));

        $dt->addRowObject($this->buildIncomingCellsRow('jan', 10530));
        $dt->addRowObject($this->buildIncomingCellsRow('feb', 9854));
        $dt->addRowObject($this->buildIncomingCellsRow('mar', 12002));
        $dt->addRowObject($this->buildIncomingCellsRow('abr', 120));

        return $dt;
    }

    /**
     * @param string    $key
     * @param float|int $value
     *
     * @return DataRow
     */
    private function buildIncomingCellsRow($key, $value)
    {
        return new DataRow(array(new DataCell($key), new DataCell($value)));
    }
}
