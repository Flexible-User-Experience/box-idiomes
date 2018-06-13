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
class ReceiptLine extends AbstractReceiptInvoiceLine
{
    /**
     * @var Receipt
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Receipt", inversedBy="lines")
     * @ORM\JoinColumn(name="receipt_id", referencedColumnName="id")
     */
    private $receipt;

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
}
