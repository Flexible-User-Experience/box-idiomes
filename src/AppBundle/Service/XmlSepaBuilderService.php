<?php

namespace AppBundle\Service;

use AppBundle\Entity\InvoiceLine;
use AppBundle\Entity\Person;
use AppBundle\Entity\Receipt;
use AppBundle\Entity\Invoice;
use AppBundle\Entity\ReceiptLine;
use AppBundle\Entity\Student;
use AppBundle\Enum\StudentPaymentEnum;
use Digitick\Sepa\Exception\InvalidArgumentException;
use Digitick\Sepa\Exception\InvalidPaymentMethodException;
use Digitick\Sepa\TransferFile\Facade\CustomerDirectDebitFacade;
use Digitick\Sepa\TransferFile\Factory\TransferFileFacadeFactory;
use Digitick\Sepa\PaymentInformation;
use Digitick\Sepa\GroupHeader;
use Digitick\Sepa\Util\StringHelper;

/**
 * Class XmlSepaBuilderService.
 *
 * @category Service
 */
class XmlSepaBuilderService
{
    const DIRECT_DEBIT_PAIN_CODE = 'pain.008.001.02';
    const DEFAULT_REMITANCE_INFORMATION = 'Import mensual';

    /**
     * @var string fiscal name
     */
    private $bn;

    /**
     * @var string fiscal identification code (CIF/DNI)
     */
    private $bd;

    /**
     * @var string IBAN code
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
        $this->ib = $this->removeSpacesFrom($ib);
        $this->bic = $this->removeSpacesFrom($bic);
    }

    /**
     * @param string    $paymentId
     * @param \DateTime $dueDate
     * @param Receipt   $recepit
     *
     * @return string
     *
     * @throws InvalidArgumentException
     * @throws InvalidPaymentMethodException
     */
    public function buildDirectDebitSingleReceiptXml($paymentId, \DateTime $dueDate, Receipt $recepit)
    {
        $this->validate($recepit);
        $directDebit = $this->buildDirectDebit($paymentId);
        $this->addPaymentInfo($directDebit, $paymentId, $dueDate);
        $this->addTransfer($directDebit, $paymentId, $recepit);

        return $directDebit->asXML();
    }

    /**
     * @param string          $paymentId
     * @param \DateTime       $dueDate
     * @param Receipt[]|array $receipts
     *
     * @return string
     *
     * @throws InvalidArgumentException
     * @throws InvalidPaymentMethodException
     */
    public function buildDirectDebitReceiptsXml($paymentId, \DateTime $dueDate, $receipts)
    {
        $directDebit = $this->buildDirectDebit($paymentId);
        $this->addPaymentInfo($directDebit, $paymentId, $dueDate);
        /** @var Receipt $receipt */
        foreach ($receipts as $receipt) {
            $this->validate($receipt);
            $this->addTransfer($directDebit, $paymentId, $receipt);
        }

        return $directDebit->asXML();
    }

    /**
     * @param string    $paymentId
     * @param \DateTime $dueDate
     * @param Invoice   $invoice
     *
     * @return string
     *
     * @throws InvalidArgumentException
     * @throws InvalidPaymentMethodException
     */
    public function buildDirectDebitSingleInvoiceXml($paymentId, \DateTime $dueDate, Invoice $invoice)
    {
        $this->validate($invoice);
        $directDebit = $this->buildDirectDebit($paymentId);
        $this->addPaymentInfo($directDebit, $paymentId, $dueDate);
        $this->addTransfer($directDebit, $paymentId, $invoice);

        return $directDebit->asXML();
    }

    /**
     * @param string          $paymentId
     * @param \DateTime       $dueDate
     * @param Invoice[]|array $invoices
     *
     * @return string
     *
     * @throws InvalidArgumentException
     * @throws InvalidPaymentMethodException
     */
    public function buildDirectDebitInvoicesXml($paymentId, \DateTime $dueDate, $invoices)
    {
        $directDebit = $this->buildDirectDebit($paymentId);
        $this->addPaymentInfo($directDebit, $paymentId, $dueDate);
        /** @var Invoice $invoice */
        foreach ($invoices as $invoice) {
            $this->validate($invoice);
            $this->addTransfer($directDebit, $paymentId, $invoice);
        }

        return $directDebit->asXML();
    }

    /**
     * @param string $paymentId
     * @param bool   $isTest
     *
     * @return CustomerDirectDebitFacade
     */
    private function buildDirectDebit($paymentId, $isTest = false)
    {
        $msgId = 'MID'.StringHelper::sanitizeString($paymentId);
        $header = new GroupHeader($msgId, $this->bn, $isTest);
        $header->setInitiatingPartyId(StringHelper::sanitizeString('NIF-'.$this->bd));

        return TransferFileFacadeFactory::createDirectDebitWithGroupHeader($header, self::DIRECT_DEBIT_PAIN_CODE);
    }

    /**
     * @param CustomerDirectDebitFacade $directDebit
     * @param string                    $paymentId
     * @param \DateTime                 $dueDate
     *
     * @throws InvalidArgumentException
     */
    private function addPaymentInfo(CustomerDirectDebitFacade &$directDebit, $paymentId, \DateTime $dueDate)
    {
        // creates a payment, it's possible to create multiple payments, "$paymentId" is the identifier for the transactions
        $directDebit->addPaymentInfo($paymentId, array(
            'id' => StringHelper::sanitizeString($paymentId),
            'dueDate' => $dueDate, // optional. Otherwise default period is used
            'creditorName' => $this->bn,
            'creditorAccountIBAN' => $this->ib,
            'creditorAgentBIC' => $this->bic,
            'seqType' => PaymentInformation::S_ONEOFF,
            'creditorId' => StringHelper::sanitizeString($this->bd),
            'localInstrumentCode' => 'CORE', // default. optional.
        ));
    }

    /**
     * @param CustomerDirectDebitFacade $directDebit
     * @param string                    $paymentId
     * @param Receipt|Invoice           $ari
     *
     * @throws InvalidArgumentException
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
            $endToEndId = $ari->getSluggedReceiptNumber();
        } elseif ($ari instanceof Invoice) {
            $endToEndId = $ari->getSluggedInvoiceNumber();
        }

        // add a Single Transaction to the named payment
        $directDebit->addTransfer($paymentId, array(
            'amount' => $ari->getTotalAmount(),
            'debtorIban' => $this->removeSpacesFrom($ari->getMainBank()->getAccountNumber()),
            'debtorBic' => $this->removeSpacesFrom($ari->getMainBank()->getSwiftCode()),
            'debtorName' => $ari->getMainEmailName(),
            'debtorMandate' => $ari->getDebtorMandate(),
            'debtorMandateSignDate' => $ari->getDebtorMandateSignDate(),
            'remittanceInformation' => $remitanceInformation,
            'endToEndId' => StringHelper::sanitizeString($endToEndId), // optional, if you want to provide additional structured info
        ));
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

    /**
     * @param Receipt|Invoice $ari
     *
     * @throws InvalidPaymentMethodException
     */
    private function validate($ari)
    {
        /** @var Student|Person $subject */
        $subject = $ari->getStudent();
        if ($subject->getParent()) {
            $subject = $subject->getParent();
        }

        if (StudentPaymentEnum::BANK_ACCOUNT_NUMBER != $subject->getPayment()) {
            throw new InvalidPaymentMethodException('Invalid payment method found in ID# '.$ari->getId());
        }
        if (!$subject->getBank()->getAccountNumber()) {
            throw new InvalidPaymentMethodException('No IBAN found in ID# '.$ari->getId());
        }
    }
}
