<?php

namespace AppBundle\Service;

use AppBundle\Entity\AbstractReceiptInvoice;
use AppBundle\Entity\AbstractReceiptInvoiceLine;
use AppBundle\Entity\Person;
use AppBundle\Entity\Receipt;
use AppBundle\Entity\Invoice;
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
    const DIRECT_DEBIT_LI_CODE = 'CORE';
    const DEFAULT_REMITANCE_INFORMATION = 'Import mensual';

    /**
     * @var SpanishSepaHelperService
     */
    private $sshs;

    /**
     * @var string fiscal name
     */
    private $bn;

    /**
     * @var string fiscal identification code (NIF/CIF/DNI)
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
     * @param SpanishSepaHelperService $sshs
     * @param string                   $bn
     * @param string                   $bd
     * @param string                   $ib
     * @param string                   $bic
     */
    public function __construct(SpanishSepaHelperService $sshs, $bn, $bd, $ib, $bic)
    {
        $this->sshs = $sshs;
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
        $directDebit = $this->buildDirectDebit($paymentId);
        $this->addPaymentInfo($directDebit, $paymentId, $dueDate);
        $this->validate($recepit);
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
        $directDebit = $this->buildDirectDebit($paymentId);
        $this->addPaymentInfo($directDebit, $paymentId, $dueDate);
        $this->validate($invoice);
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
        $header = new GroupHeader($msgId, strtoupper(StringHelper::sanitizeString($this->bn)), $isTest);
        $header->setCreationDateTimeFormat('Y-m-d\TH:i:s');
        $header->setInitiatingPartyId($this->sshs->getSpanishCreditorIdFromNif($this->bd));

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
        $directDebit->addPaymentInfo($paymentId, array(
            'id' => StringHelper::sanitizeString($paymentId),
            'dueDate' => $dueDate,
            'creditorName' => strtoupper(StringHelper::sanitizeString($this->bn)),
            'creditorAccountIBAN' => $this->ib,
            'creditorAgentBIC' => $this->bic,
            'seqType' => PaymentInformation::S_ONEOFF,
            'creditorId' => $this->sshs->getSpanishCreditorIdFromNif($this->bd),
            'localInstrumentCode' => self::DIRECT_DEBIT_LI_CODE,
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
            /** @var AbstractReceiptInvoiceLine $firstLine */
            $firstLine = $ari->getLines()[0];
            $remitanceInformation = $firstLine->getDescription();
        }

        $endToEndId = '';
        if ($ari instanceof Receipt) {
            $endToEndId = $ari->getSluggedReceiptNumber();
        } elseif ($ari instanceof Invoice) {
            $endToEndId = $ari->getSluggedInvoiceNumber();
        }

        $transferInformation = array(
            'amount' => $ari->getTotalAmount(),
            'debtorIban' => $this->removeSpacesFrom($ari->getMainBank()->getAccountNumber()),
            'debtorName' => $ari->getMainEmailName(),
            'debtorMandate' => $ari->getDebtorMandate(),
            'debtorMandateSignDate' => $ari->getDebtorMandateSignDate(),
            'remittanceInformation' => $remitanceInformation,
            'endToEndId' => StringHelper::sanitizeString($endToEndId),
        );

        if ($ari->getMainBank()->getSwiftCode()) {
            $transferInformation['debtorBic'] = $this->removeSpacesFrom($ari->getMainBank()->getSwiftCode());
        }

        $directDebit->addTransfer($paymentId, $transferInformation);
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
     * @param AbstractReceiptInvoice $ari
     *
     * @throws InvalidPaymentMethodException
     */
    private function validate($ari)
    {
        /** @var Student|Person $subject */
        $subject = $ari->getMainSubject();
        if (StudentPaymentEnum::BANK_ACCOUNT_NUMBER != $subject->getPayment()) {
            throw new InvalidPaymentMethodException('Forma de pagament invàlida al '.($subject instanceof Student ? 'alumne' : 'pare/mare').' '.$subject->getFullName());
        }
        if (!$subject->getBank()->getAccountNumber()) {
            throw new InvalidPaymentMethodException('No s\'ha trobat cap IBAN al '.($subject instanceof Student ? 'alumne' : 'pare/mare').' '.$subject->getFullName());
        }
    }
}
