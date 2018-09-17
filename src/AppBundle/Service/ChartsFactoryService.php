<?php

namespace AppBundle\Service;

use AppBundle\Enum\ReceiptYearMonthEnum;
use AppBundle\Repository\InvoiceRepository;
use AppBundle\Repository\ReceiptRepository;
use SaadTazi\GChartBundle\DataTable\DataRow;
use SaadTazi\GChartBundle\DataTable\DataCell;
use SaadTazi\GChartBundle\DataTable\DataTable;
use SaadTazi\GChartBundle\DataTable\DataColumn;
use Symfony\Bundle\FrameworkBundle\Translation\Translator;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * Class ChartsFactoryService.
 *
 * @category Service
 */
class ChartsFactoryService
{
    /**
     * @var Translator
     */
    private $ts;

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
     * @param TranslatorInterface $ts
     * @param ReceiptRepository   $rr
     * @param InvoiceRepository   $ir
     */
    public function __construct(TranslatorInterface $ts, ReceiptRepository $rr, InvoiceRepository $ir)
    {
        $this->ts = $ts;
        $this->rr = $rr;
        $this->ir = $ir;
    }

    /**
     * @return DataTable
     *
     * @throws \SaadTazi\GChartBundle\DataTable\Exception\InvalidColumnTypeException
     * @throws \Exception
     */
    public function buildLastYearIncomingsChart()
    {
        $dt = new DataTable();
        $dt->addColumnObject(new DataColumn('id1', 'title', 'string'));
        $dt->addColumnObject(new DataColumn('id2', $this->ts->trans('backend.admin.block.charts.incomings', array(), 'messages'), 'number'));

        $date = new \DateTime();
        $date->sub(new \DateInterval('P12M'));
        $interval = new \DateInterval('P1M');
        for ($i = 0; $i <= 12; ++$i) {
            $receipts = $this->rr->getMonthlyIncomingsAmountForDate($date);
            $invoices = $this->ir->getMonthlyIncomingsAmountForDate($date);
            $dt->addRowObject($this->buildIncomingCellsRow($date, $receipts + $invoices));
            $date->add($interval);
        }

        return $dt;
    }

    /**
     * @param \DateTime $key
     * @param float|int $value
     *
     * @return DataRow
     */
    private function buildIncomingCellsRow($key, $value)
    {
        return new DataRow(array(new DataCell($key->format('U'), ReceiptYearMonthEnum::getTranslatedMonthEnumArray()[intval($key->format('n'))].'\''.$key->format('y')), new DataCell($value, number_format($value, 0, ',', '.').' €')));
    }
}
