<?php

namespace AppBundle\Form\Model;

use AppBundle\Entity\Student;

/**
 * Class GenerateInvoiceItemModel.
 *
 * @category FormModel
 *
 * @author   David Romaní <david@flux.cat>
 */
class GenerateInvoiceItemModel
{
    /**
     * @var Student
     */
    private $student;

    /**
     * @var float
     */
    private $units;

    /**
     * @var float
     */
    private $unitPrice;

    /**
     * Methods.
     */

    /**
     * @return Student
     */
    public function getStudent()
    {
        return $this->student;
    }

    /**
     * @param Student $student
     *
     * @return $this
     */
    public function setStudent($student)
    {
        $this->student = $student;

        return $this;
    }

    /**
     * @return float
     */
    public function getUnits()
    {
        return $this->units;
    }

    /**
     * @param float $units
     *
     * @return $this
     */
    public function setUnits($units)
    {
        $this->units = $units;

        return $this;
    }

    /**
     * @return float
     */
    public function getUnitPrice()
    {
        return $this->unitPrice;
    }

    /**
     * @param float $unitPrice
     *
     * @return $this
     */
    public function setUnitPrice($unitPrice)
    {
        $this->unitPrice = $unitPrice;

        return $this;
    }
}
