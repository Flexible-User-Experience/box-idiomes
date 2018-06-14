<?php

namespace AppBundle\Manager;

use AppBundle\Entity\Invoice;
use AppBundle\Entity\InvoiceLine;
use AppBundle\Entity\Receipt;
use AppBundle\Repository\InvoiceRepository;

/**
 * Class ReceiptManager.
 *
 * @category Manager
 */
class ReceiptManager
{
    /**
     * @var InvoiceRepository
     */
    private $ir;

    /**
     * Methods.
     */

    /**
     * ReceiptManager constructor.
     *
     * @param InvoiceRepository $ir
     */
    public function __construct(InvoiceRepository $ir)
    {
        $this->ir = $ir;
    }

    /**
     * @param Receipt $receipt
     *
     * @return Invoice
     */
    public function createInvoiceFromReceipt(Receipt $receipt)
    {
        $invoice = new Invoice();
        $invoice
            ->setReceipt($receipt)
            ->setStudent($receipt->getStudent())
            ->setPerson($receipt->getPerson())
            ->setDate($receipt->getDate())
            ->setIsPayed($receipt->getIsPayed() ? $receipt->getIsPayed() : false)
            ->setPaymentDate($receipt->getPaymentDate() ? $receipt->getPaymentDate() : null)
            ->setBaseAmount($receipt->getBaseAmount())
            ->setDiscountApplied($receipt->isDiscountApplied())
            ->setMonth($receipt->getMonth())
            ->setYear($receipt->getYear())
            ->setIsSended(false)
        ;
        foreach ($receipt->getLines() as $line) {
            $invoiceLine = new InvoiceLine();
            $invoiceLine
                ->setInvoice($invoice)
                ->setDescription($line->getDescription())
                ->setUnits($line->getUnits())
                ->setPriceUnit($line->getPriceUnit())
                ->setDiscount($line->getDiscount())
                ->setTotal($line->calculateBaseAmount())
            ;
            $invoice->addLine($invoiceLine);
        }
        // TODO set IRPF, IVA, etc..

        return $invoice;
    }

    /**
     * @param Receipt $receipt
     *
     * @return bool
     */
    public function isReceiptInvoiced(Receipt $receipt)
    {
        $searchedInvoice = $this->ir->findOneBy(
            array(
                'receipt' => $receipt,
            )
        );

        return is_null($searchedInvoice) ? false : true;
    }
}
