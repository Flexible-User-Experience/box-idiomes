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
 * @author   David Romaní <david@flux.cat>
 *
 * @ORM\Entity(repositoryClass="AppBundle\Repository\InvoiceRepository")
 * @ORM\Table(name="invoice")
 * @UniqueEntity(fields={"month", "year", "student", "person"})
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
    private $taxParcentage = self::TAX_IVA;

    /**
     * @var float
     *
     * @ORM\Column(type="float", nullable=true, options={"default"=15})
     */
    private $irpf = self::TAX_IRPF;

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
     * @return float
     */
    public function getIrpf()
    {
        return $this->irpf;
    }

    /**
     * @param float $irpf
     *
     * @return Invoice
     */
    public function setIrpf($irpf)
    {
        $this->irpf = $irpf;

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

        return $date->format('Y').'_'.$this->getId();
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
     * @return float|int
     */
    public function calculateTaxParcentage()
    {
        return $this->calculateTotalBaseAmount() * (self::TAX_IVA / 100);
    }

    /**
     * @return float|int
     */
    public function calculateIrpf()
    {
        return $this->calculateTotalBaseAmount() * (self::TAX_IRPF / 100);
    }

    /**
     * @return float|int
     */
    public function calculateTotal()
    {
        return $this->calculateTotalBaseAmount() + $this->calculateTaxParcentage() - $this->calculateIrpf();
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->id ? $this->getInvoiceNumber().' · '.$this->getStudent().' · '.$this->getTotalAmountString() : '---';
    }
}
