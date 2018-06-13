<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class ReceiptLine.
 *
 * @category Entity
 *
 * @author   David Romaní <david@flux.cat>
 *
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ReceiptLineRepository")
 * @ORM\Table(name="receipt_line")
 */
class ReceiptLine extends AbstractBase
{
    /**
     * @var Receipt
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Receipt", inversedBy="lines")
     * @ORM\JoinColumn(name="invoice_id", referencedColumnName="id")
     */
    private $receipt;

    /**
     * @var Student
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Student")
     * @ORM\JoinColumn(name="student_id", referencedColumnName="id")
     */
    private $student;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $description;

    /**
     * @var float
     *
     * @ORM\Column(type="float")
     */
    private $units;

    /**
     * @var float
     *
     * @ORM\Column(type="float")
     */
    private $priceUnit;

    /**
     * @var float
     *
     * @ORM\Column(type="float", nullable=true)
     */
    private $discount;

    /**
     * @var float
     *
     * @ORM\Column(type="float", nullable=true)
     */
    private $total;

    /**
     * Methods.
     */

    /**
     * @return Receipt
     */
    public function getReceipt()
    {
        return $this->receipt;
    }

    /**
     * @param Receipt $receipt
     *
     * @return $this
     */
    public function setReceipt(Receipt $receipt)
    {
        $this->receipt = $receipt;

        return $this;
    }

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
    public function setStudent(Student $student)
    {
        $this->student = $student;

        return $this;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     *
     * @return $this
     */
    public function setDescription($description)
    {
        $this->description = $description;

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
    public function getPriceUnit()
    {
        return $this->priceUnit;
    }

    /**
     * @param float $priceUnit
     *
     * @return $this
     */
    public function setPriceUnit($priceUnit)
    {
        $this->priceUnit = $priceUnit;

        return $this;
    }

    /**
     * @return float
     */
    public function getDiscount()
    {
        return $this->discount;
    }

    /**
     * @param float $discount
     *
     * @return $this
     */
    public function setDiscount($discount)
    {
        $this->discount = $discount;

        return $this;
    }

    /**
     * @return float
     */
    public function getTotal()
    {
        return $this->total;
    }

    /**
     * @param float $total
     *
     * @return $this
     */
    public function setTotal($total)
    {
        $this->total = $total;

        return $this;
    }

    /**
     * @return float
     */
    public function calculateBaseAmount()
    {
        return $this->units * $this->priceUnit - $this->discount;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->id ? $this->getDescription() : '---';
    }
}
