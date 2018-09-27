<?php

namespace AppBundle\Service;

use AppBundle\Enum\ReceiptYearMonthEnum;
use AppBundle\Repository\InvoiceRepository;
use AppBundle\Repository\ReceiptRepository;
use AppBundle\Repository\SpendingRepository;
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
     * @var SpendingRepository
     */
    private $sr;

    /**
     * Methods.
     */

    /**
     * ChartsFactoryService constructor.
     *
     * @param TranslatorInterface $ts
     * @param ReceiptRepository   $rr
     * @param InvoiceRepository   $ir
     * @param SpendingRepository  $sr
     */
    public function __construct(TranslatorInterface $ts, ReceiptRepository $rr, InvoiceRepository $ir, SpendingRepository $sr)
    {
        $this->ts = $ts;
        $this->rr = $rr;
        $this->ir = $ir;
        $this->sr = $sr;
    }

    /**
     * @return DataTable
     *
     * @throws \SaadTazi\GChartBundle\DataTable\Exception\InvalidColumnTypeException
     * @throws \Exception
     */
    public function buildLastYearResultsChart()
    {
        $dt = new DataTable();
        $dt->addColumnObject(new DataColumn('id1', 'title', 'string'));
        $dt->addColumnObject(new DataColumn('id2', $this->ts->trans('backend.admin.block.charts.sales', array(), 'messages'), 'number'));
        $dt->addColumnObject(new DataColumn('id3', $this->ts->trans('backend.admin.block.charts.expenses', array(), 'messages'), 'number'));
        $dt->addColumnObject(new DataColumn('id4', $this->ts->trans('backend.admin.block.charts.results', array(), 'messages'), 'number'));

        $date = new \DateTime();
        $date->sub(new \DateInterval('P12M'));
        $interval = new \DateInterval('P1M');
        for ($i = 0; $i <= 12; ++$i) {
            $sales = $this->rr->getMonthlyIncomingsAmountForDate($date);
            $sales = $sales + $this->ir->getMonthlyIncomingsAmountForDate($date);
            $expenses = $this->sr->getMonthlyExpensesAmountForDate($date);
            $results = $sales - $expenses;
            $dt->addRowObject($this->buildResultsCellsRow($date, $sales, $expenses, $results));
            $date->add($interval);
        }

        return $dt;
    }

    /**
     * @param \DateTime $key
     * @param float|int $sales
     * @param float|int $expenses
     * @param float|int $results
     *
     * @return DataRow
     */
    private function buildResultsCellsRow($key, $sales, $expenses, $results)
    {
        return new DataRow(array(
                new DataCell(ReceiptYearMonthEnum::getShortTranslatedMonthEnumArray()[intval($key->format('n'))].'. '.$key->format('y')),
                new DataCell($sales, number_format($sales, 0, ',', '.').'€'),
                new DataCell($expenses, number_format($sales, 0, ',', '.').'€'),
                new DataCell($results, number_format($results, 0, ',', '.').'€'),
            )
        );
    }
}
