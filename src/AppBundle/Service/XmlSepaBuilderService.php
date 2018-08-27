<?php

namespace AppBundle\Service;

use AppBundle\Entity\InvoiceLine;
use AppBundle\Entity\Receipt;
use AppBundle\Entity\Invoice;
use AppBundle\Entity\ReceiptLine;
use Digitick\Sepa\TransferFile\Facade\CustomerDirectDebitFacade;
use Digitick\Sepa\TransferFile\Factory\TransferFileFacadeFactory;
use Digitick\Sepa\PaymentInformation;
use Digitick\Sepa\GroupHeader;

/**
 * Class XmlSepaBuilderService.
 *
 * @category Service
 */
class XmlSepaBuilderService
{
    const DIRECT_DEBIT_PAIN_CODE = 'pain.008.001.02';
    const DEFAULT_REMITANCE_INFORMATION = 'Rebut mensual';

    /**
     * @var string boss name
     */
    private $bn;

    /**
     * @var string boss DNI
     */
    private $bd;

    /**
     * @var string IBAN bussines
     */
    private $ib;

    /**
     * @var string BIC number
     */
    private $bic;

    /**
     * Methods.
     */

    /**
     * XmlSepaBuilderService constructor.
     *
     * @param string $bn
     * @param string $bd
     * @param string $ib
     * @param string $bic
     */
    public function __construct($bn, $bd, $ib, $bic)
    {
        $this->bn = $bn;
        $this->bd = $bd;
        $this->ib = $ib;
        $this->bic = $bic;
    }

    /**
     * @param string    $paymentId
     * @param \DateTime $dueDate
     * @param Receipt   $receipt
     *
     * @return string
     *
     * @throws \Digitick\Sepa\Exception\InvalidArgumentException
     */
    public function buildDirectDebitReceiptXml($paymentId, \DateTime $dueDate, Receipt $receipt)
    {
        return $this->buildDirectDebitXml($paymentId, $dueDate, $receipt);
    }

    /**
     * @param string    $paymentId
     * @param \DateTime $dueDate
     * @param Invoice   $invoice
     *
     * @return string
     *
     * @throws \Digitick\Sepa\Exception\InvalidArgumentException
     */
    public function buildDirectDebitInvoiceXml($paymentId, \DateTime $dueDate, Invoice $invoice)
    {
        return $this->buildDirectDebitXml($paymentId, $dueDate, $invoice);
    }

    /**
     * @return CustomerDirectDebitFacade
     */
    private function buildDirectDebit()
    {
        $today = new \DateTime();
        $header = new GroupHeader($today->format('Y-m-d-H-i-s'), $this->bn);
        $header->setInitiatingPartyId($this->bd);

        return TransferFileFacadeFactory::createDirectDebitWithGroupHeader($header, self::DIRECT_DEBIT_PAIN_CODE);
    }

    /**
     * @param CustomerDirectDebitFacade $directDebit
     * @param string                    $paymentId
     * @param \DateTime                 $dueDate
     *
     * @throws \Digitick\Sepa\Exception\InvalidArgumentException
     */
    private function addPaymentInfo(CustomerDirectDebitFacade &$directDebit, $paymentId, \DateTime $dueDate)
    {
        // creates a payment, it's possible to create multiple payments, "$paymentId" is the identifier for the transactions
        $directDebit->addPaymentInfo($paymentId, array(
            'id' => $paymentId,
            'dueDate' => $dueDate, // optional. Otherwise default period is used
            'creditorName' => $this->bn,
            'creditorAccountIBAN' => $this->removeSpacesFrom($this->ib),
            'creditorAgentBIC' => $this->removeSpacesFrom($this->bic),
            'seqType' => PaymentInformation::S_ONEOFF,
            'creditorId' => $this->bd,
            'localInstrumentCode' => 'CORE', // default. optional.
        ));
    }

    /**
     * @param CustomerDirectDebitFacade $directDebit
     * @param string                    $paymentId
     * @param Receipt|Invoice           $ari
     *
     * @throws \Digitick\Sepa\Exception\InvalidArgumentException
     */
    private function addTransfer(CustomerDirectDebitFacade &$directDebit, $paymentId, $ari)
    {
        $remitanceInformation = self::DEFAULT_REMITANCE_INFORMATION;
        if (count($ari->getLines()) > 0) {
            /** @var ReceiptLine|InvoiceLine $firstLine */
            $firstLine = $ari->getLines()[0];
            $remitanceInformation = $firstLine->getDescription();
        }

        $endToEndId = '';
        if ($ari instanceof Receipt) {
            $endToEndId = 'Rebut num. '.$ari->getSluggedReceiptNumber();
        } elseif ($ari instanceof Invoice) {
            $endToEndId = 'Factura num. '.$ari->getSluggedInvoiceNumber();
        }

        // add a Single Transaction to the named payment
        $directDebit->addTransfer($paymentId, array(
            'amount' => $ari->getBaseAmount(),
            'debtorIban' => $this->removeSpacesFrom($ari->getMainBank()->getAccountNumber()),
            'debtorBic' => $this->removeSpacesFrom($ari->getMainBank()->getSwiftCode()),
            'debtorName' => $ari->getMainEmailName(),
            'debtorMandate' => $ari->getDebtorMandate(),
            'debtorMandateSignDate' => $ari->getDebtorMandateSignDate(),
            'remittanceInformation' => $remitanceInformation,
            'endToEndId' => $endToEndId, // optional, if you want to provide additional structured info
        ));
    }

    /**
     * @param string          $paymentId
     * @param \DateTime       $dueDate
     * @param Receipt|Invoice $ari
     *
     * @return string
     *
     * @throws \Digitick\Sepa\Exception\InvalidArgumentException
     */
    private function buildDirectDebitXml($paymentId, \DateTime $dueDate, $ari)
    {
        $directDebit = $this->buildDirectDebit();
        $this->addPaymentInfo($directDebit, $paymentId, $dueDate);
        $this->addTransfer($directDebit, $paymentId, $ari);

        return $directDebit->asXML();
    }

    /**
     * @param string $value
     *
     * @return string
     */
    private function removeSpacesFrom($value)
    {
        return str_replace(' ', '', $value);
    }
}
