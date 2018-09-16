<?php

namespace AppBundle\Service;

use AppBundle\Repository\InvoiceRepository;

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
     * @return Highchart
     */
    public function buildInvoicesHighchart()
    {
        // TODO get values from Invoice repository
        $series = array(
            array(
                'name' => 'Data Serie Name',
                'data' => array(1, 2, 4, 5, 6, 3, 8),
            ),
        );

        $ob = new Highchart();
        $ob->chart->renderTo('linechart');  // The #id of the div where to render the chart
        $ob->title->text('Chart Title');
        $ob->xAxis->title(array('text' => 'Horizontal axis title'));
        $ob->yAxis->title(array('text' => 'Vertical axis title'));
        $ob->series($series);

        return $ob;
    }
}
