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
 * @ORM\Entity(repositoryClass="AppBundle\Repository\InvoiceRepository")
 * @ORM\Table(name="invoice")
 * @UniqueEntity(fields={"month", "year", "student", "person", "isForPrivateLessons"})
 */
class Invoice extends AbstractReceiptInvoice
{
    const TAX_IRPF = 15;
    const TAX_IVA = 0;

    /**
     * @var Receipt
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Receipt")
     * @ORM\JoinColumn(name="receipt_id", referencedColumnName="id")
     */
    private $receipt;

    /**
     * @var ArrayCollection|array|InvoiceLine[]
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\InvoiceLine", mappedBy="invoice", cascade={"persist", "remove"}, orphanRemoval=true)
     */
    private $lines;

    /**
     * @var float
     *
     * @ORM\Column(type="float", options={"default"=0})
     */
    private $taxPercentage = self::TAX_IVA;

    /**
     * @var float
     *
     * @ORM\Column(type="float", nullable=true, options={"default"=15})
     */
    private $irpfPercentage = self::TAX_IRPF;

    /**
     * @var float
     *
     * @ORM\Column(type="float", nullable=true)
     */
    private $totalAmount;

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
    public function setReceipt($receipt)
    {
        $this->receipt = $receipt;

        return $this;
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
    public function basicAddLine(InvoiceLine $line)
    {
        $this->lines->add($line);

        return $this;
    }

    /**
     * @param InvoiceLine $line
     *
     * @return $this
     */
    public function addLine(InvoiceLine $line)
    {
        if (!$this->lines->contains($line)) {
            $line->setInvoice($this);
            $this->lines->add($line);
            $this->setBaseAmount($this->getBaseAmount() + $line->getTotal());
            $this->setDiscountApplied($this->getStudent()->hasDiscount());
        }

        return $this;
    }

    /**
     * @param InvoiceLine $line
     *
     * @return $this
     */
    public function removeLine(InvoiceLine $line)
    {
        if ($this->lines->contains($line)) {
            $this->lines->removeElement($line);
            $this->setBaseAmount($this->getBaseAmount() - $line->getTotal());
        }

        return $this;
    }

    /**
     * @return float
     */
    public function getTaxPercentage()
    {
        return $this->taxPercentage;
    }

    /**
     * @param float $taxPercentage
     *
     * @return Invoice
     */
    public function setTaxPercentage($taxPercentage)
    {
        $this->taxPercentage = $taxPercentage;

        return $this;
    }

    /**
     * @return float
     */
    public function getIrpfPercentage()
    {
        return $this->irpfPercentage;
    }

    /**
     * @param float $irpfPercentage
     *
     * @return Invoice
     */
    public function setIrpfPercentage($irpfPercentage)
    {
        $this->irpfPercentage = $irpfPercentage;

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
     * @return string
     */
    public function getTotalAmountString()
    {
        return number_format($this->totalAmount, 2, ',', '.').'€';
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
     * @return string
     */
    public function getInvoiceNumber()
    {
        $date = new \DateTime();
        if ($this->getDate()) {
            $date = $this->getDate();
        }

        return $date->format('Y').'/'.$this->getId();
    }

    /**
     * @return string
     */
    public function getSluggedInvoiceNumber()
    {
        $date = new \DateTime();
        if ($this->getDate()) {
            $date = $this->getDate();
        }

        return $date->format('Y').'-'.$this->getId();
    }

    /**
     * @return string
     */
    public function getUnderscoredInvoiceNumber()
    {
        $date = new \DateTime();
        if ($this->getDate()) {
            $date = $this->getDate();
        }

        return $date->format('Y').'_'.$this->getId();
    }

    /**
     * @return float
     */
    public function calculateBaseAmount()
    {
        $result = 0.0;
        /** @var InvoiceLine $line */
        foreach ($this->lines as $line) {
            $result = $result + $line->calculateBaseAmount();
        }

        return $result;
    }

    /**
     * @return float|int
     */
    public function calculateTaxPercentage()
    {
        return $this->getBaseAmount() * ($this->getTaxPercentage() / 100);
    }

    /**
     * @return float|int
     */
    public function calculateIrpfPercentatge()
    {
        return $this->getBaseAmount() * ($this->getIrpfPercentage() / 100);
    }

    /**
     * @return float|int
     */
    public function calculateTotalAmount()
    {
        return $this->getBaseAmount() + $this->calculateTaxPercentage() - $this->calculateIrpfPercentatge();
    }

    /**
     * @param int|float $value
     *
     * @return float
     */
    public function calculateIrpfOverhead($value)
    {
        return $value / (1 - ($this->getIrpfPercentage() / 100));
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->id ? $this->getInvoiceNumber().' · '.$this->getStudent().' · '.$this->getTotalAmountString() : '---';
    }
}
