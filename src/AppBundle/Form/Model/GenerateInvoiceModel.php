<?php

namespace AppBundle\Form\Type;

/**
 * Class GenerateInvoiceModel.
 *
 * @category FormModel
 *
 * @author   David Romaní <david@flux.cat>
 */
class GenerateInvoiceModel
{
    /**
     * @var int
     */
    private $year;

    /**
     * @var int
     */
    private $month;

    /**
     * Methods.
     */

    /**
     * @return int
     */
    public function getYear()
    {
        return $this->year;
    }

    /**
     * @param int $year
     *
     * @return $this
     */
    public function setYear($year)
    {
        $this->year = $year;

        return $this;
    }

    /**
     * @return int
     */
    public function getMonth()
    {
        return $this->month;
    }

    /**
     * @param int $month
     *
     * @return $this
     */
    public function setMonth($month)
    {
        $this->month = $month;

        return $this;
    }
}
