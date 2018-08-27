<?php

namespace AppBundle\Service;

use AppBundle\Entity\Receipt;
use AppBundle\Entity\ReceiptLine;
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
        $today = new \DateTime();
        $remitanceInformation = self::DEFAULT_REMITANCE_INFORMATION;
        if (count($receipt->getLines()) > 0) {
            /** @var ReceiptLine $firstLine */
            $firstLine = $receipt->getLines()[0];
            $remitanceInformation = $firstLine->getDescription();
        }

        $header = new GroupHeader($today->format('Y-m-d-H-i-s'), $this->bn);
        $header->setInitiatingPartyId('DE21WVM1234567890');

        $directDebit = TransferFileFacadeFactory::createDirectDebitWithGroupHeader($header, self::DIRECT_DEBIT_PAIN_CODE);

        // creates a payment, it's possible to create multiple payments, "$paymentId" is the identifier for the transactions
        $directDebit->addPaymentInfo($paymentId, array(
            'id' => $paymentId,
            'dueDate' => $dueDate, // optional. Otherwise default period is used
            'creditorName' => $this->bn,
            'creditorAccountIBAN' => $this->ib,
            'creditorAgentBIC' => $this->bic,
            'seqType' => PaymentInformation::S_ONEOFF,
            'creditorId' => $this->bd,
            'localInstrumentCode' => 'CORE', // default. optional.
        ));

        // add a Single Transaction to the named payment
        $directDebit->addTransfer($paymentId, array(
            'amount' => $receipt->getBaseAmount(),
            'debtorIban' => $receipt->getMainBank()->getAccountNumber(),
            'debtorBic' => $receipt->getMainBank()->getSwiftCode(),
            'debtorName' => $receipt->getMainEmailName(),
            'debtorMandate' => 'AB12345',     // TODO
            'debtorMandateSignDate' => '13.10.2012',  // TODO
            'remittanceInformation' => $remitanceInformation,
            'endToEndId' => 'Rebut num. '.$receipt->getSluggedReceiptNumber(), // optional, if you want to provide additional structured info
        ));

        return $directDebit->asXML();
    }
}
