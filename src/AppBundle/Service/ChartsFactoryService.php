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
        $dt = new DataTable();
        $dt->addColumnObject(new DataColumn('id1', 'title', 'string'));
        $dt->addColumnObject(new DataColumn('id2', 'incomings', 'number'));

        $today = new \DateTime();
        $today->sub(new \DateInterval('P11M'));
        $interval = new \DateInterval('P1M');
        for ($i = 1; $i <= 12; ++$i) {
            $dt->addRowObject($this->buildIncomingCellsRow($today->format('m/y'), 10530 + ($i * 100)));
            $today->add($interval);
        }

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
