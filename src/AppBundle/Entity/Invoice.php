<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Class Invoice.
 *
 * @category Entity
 *
 * @author   Wils Iglesias <wiglesias83@gmail.com>
 *
 * @ORM\Entity(repositoryClass="AppBundle\Repository\InvoiceRepository")
 * @ORM\Table(name="invoice")
 * @UniqueEntity({"month", "year", "student"})
 * @UniqueEntity({"month", "year", "person"})
 */
class Invoice extends AbstractBase
{
    /**
     * @var ArrayCollection|array|InvoiceLine[]
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\InvoiceLine", mappedBy="invoice")
     */
    private $lines;

    /**
     * @var Student
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Student")
     * @ORM\JoinColumn(name="student_id", referencedColumnName="id")
     */
    private $student;

    /**
     * @var Person
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Person")
     * @ORM\JoinColumn(name="person_id", referencedColumnName="id")
     */
    private $person;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="date", nullable=true)
     */
    private $date;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean")
     */
    private $isPayed;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="date")
     */
    private $paymentDate;

    /**
     * @var float
     *
     * @ORM\Column(type="float")
     */
    private $baseAmount;

    /**
     * @var float
     *
     * @ORM\Column(type="float", options={"default"=0})
     */
    private $taxParcentage = 0;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean")
     */
    private $discountApplied;

    /**
     * @var float
     *
     * @ORM\Column(type="float")
     */
    private $totalAmount;

    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     */
    private $month;

    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     */
    private $year;

    /**
     * Methods.
     */

    /**
     * Invoice constructor.
     */
    public function __construct()
    {
        $this->lines = new ArrayCollection();
    }

    /**
     * @return InvoiceLine[]|array|ArrayCollection
     */
    public function getLines()
    {
        return $this->lines;
    }

    /**
     * @param InvoiceLine[]|array|ArrayCollection $lines
     *
     * @return $this
     */
    public function setLines($lines)
    {
        $this->lines = $lines;

        return $this;
    }

    /**
     * @param InvoiceLine $line
     *
     * @return $this
     */
    public function addLine($line)
    {
        if (!$this->lines->contains($line)) {
            $this->lines->add($line);
        }

        return $this;
    }

    /**
     * @param InvoiceLine $line
     *
     * @return $this
     */
    public function removeLine($line)
    {
        if ($this->lines->contains($line)) {
            $this->lines->removeElement($line);
        }

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
     * @return Invoice
     */
    public function setStudent($student)
    {
        $this->student = $student;

        return $this;
    }

    /**
     * @return Person
     */
    public function getPerson()
    {
        return $this->person;
    }

    /**
     * @param Person $person
     *
     * @return Invoice
     */
    public function setPerson($person)
    {
        $this->person = $person;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param \DateTime $date
     *
     * @return Invoice
     */
    public function setDate(\DateTime $date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * @return bool
     */
    public function isPayed()
    {
        return $this->isPayed;
    }

    /**
     * @param bool $isPayed
     *
     * @return Invoice
     */
    public function setIsPayed($isPayed)
    {
        $this->isPayed = $isPayed;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getPaymentDate()
    {
        return $this->paymentDate;
    }

    /**
     * @param \DateTime $paymentDate
     *
     * @return Invoice
     */
    public function setPaymentDate(\DateTime $paymentDate)
    {
        $this->paymentDate = $paymentDate;

        return $this;
    }

    /**
     * @return float
     */
    public function getBaseAmount()
    {
        return $this->baseAmount;
    }

    /**
     * @param float $baseAmount
     *
     * @return Invoice
     */
    public function setBaseAmount($baseAmount)
    {
        $this->baseAmount = $baseAmount;

        return $this;
    }

    /**
     * @return float
     */
    public function getTaxParcentage()
    {
        return $this->taxParcentage;
    }

    /**
     * @param float $taxParcentage
     *
     * @return Invoice
     */
    public function setTaxParcentage($taxParcentage)
    {
        $this->taxParcentage = $taxParcentage;

        return $this;
    }

    /**
     * @return bool
     */
    public function isDiscountApplied()
    {
        return $this->discountApplied;
    }

    /**
     * @param bool $discountApplied
     *
     * @return Invoice
     */
    public function setDiscountApplied($discountApplied)
    {
        $this->discountApplied = $discountApplied;

        return $this;
    }

    /**
     * @return float
     */
    public function getTotalAmount()
    {
        return $this->totalAmount;
    }

    /**
     * @param float $totalAmount
     *
     * @return Invoice
     */
    public function setTotalAmount($totalAmount)
    {
        $this->totalAmount = $totalAmount;

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
     * @return Invoice
     */
    public function setMonth($month)
    {
        $this->month = $month;

        return $this;
    }

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
     * @return Invoice
     */
    public function setYear($year)
    {
        $this->year = $year;

        return $this;
    }

    /**
     * @return string
     */
    public function getInvoiceNumber()
    {
        return  $this->getDate()->format('Y').'-'.$this->getId();
    }

    /**
     * @return float
     */
    public function calculateTotalBaseAmount()
    {
        $result = 0.0;
        /** @var InvoiceLine $line */
        foreach ($this->lines as $line) {
            $result = $result + $line->calculateBaseAmount();
        }

        return $result;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->id ? $this->getYear().'/'.$this->id : '---';
    }
}
