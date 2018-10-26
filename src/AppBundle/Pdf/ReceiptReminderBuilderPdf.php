<?php

namespace AppBundle\Pdf;

use AppBundle\Entity\Receipt;
use AppBundle\Enum\StudentPaymentEnum;
use AppBundle\Service\SmartAssetsHelperService;
use Symfony\Bundle\FrameworkBundle\Translation\Translator;
use WhiteOctober\TCPDFBundle\Controller\TCPDFController;

/**
 * Class ReceiptReminderBuilderPdf.
 *
 * @category Service
 */
class ReceiptReminderBuilderPdf extends AbstractReceiptInvoiceBuilderPdf
{
    /**
     * ReceiptBuilderPdf constructor.
     *
     * @param TCPDFController          $tcpdf
     * @param SmartAssetsHelperService $sahs
     * @param Translator               $ts
     * @param string                   $pwt    project web title
     * @param string                   $bn     boss name
     * @param string                   $bd     boss DNI
     * @param string                   $ba     boss address
     * @param string                   $bc     boss city
     * @param string                   $ib     IBAN bussines
     * @param string                   $locale default locale useful in CLI
     */
    public function __construct(TCPDFController $tcpdf, SmartAssetsHelperService $sahs, Translator $ts, $pwt, $bn, $bd, $ba, $bc, $ib, $locale)
    {
        parent::__construct($tcpdf, $sahs, $ts, $pwt, $bn, $bd, $ba, $bc, $ib, $locale);
    }

    /**
     * @param Receipt $receipt
     *
     * @return \TCPDF
     */
    public function build(Receipt $receipt)
    {
        if ($this->sahs->isCliContext()) {
            $this->ts->setLocale($this->locale);
        }

        /** @var BaseTcpdf $pdf */
        $pdf = $this->tcpdf->create($this->sahs);
        $subject = $receipt->getStudent();

        // set document information
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor($this->pwt);
        $pdf->SetTitle($this->ts->trans('backend.admin.receipt_reminder.title').' '.$receipt->getReceiptNumber());
        $pdf->SetSubject($this->ts->trans('backend.admin.invoice.detail').' '.$this->ts->trans('backend.admin.receipt_reminder.title').' '.$receipt->getReceiptNumber());
        // set default font subsetting mode
        $pdf->setFontSubsetting(true);
        // remove default header/footer
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        // set default monospaced font
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
        // set margins
        $pdf->SetMargins(BaseTcpdf::PDF_A5_MARGIN_LEFT, BaseTcpdf::PDF_A5_MARGIN_TOP, BaseTcpdf::PDF_A5_MARGIN_RIGHT);
        $pdf->SetAutoPageBreak(true, BaseTcpdf::PDF_A5_MARGIN_BOTTOM);
        // set image scale factor
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
        // Add start page
        $pdf->AddPage('L', 'A5', true, true);

        // logo
        $pdf->Image($this->sahs->getAbsoluteAssetFilePath('/bundles/app/img/logo-pdf.png'), BaseTcpdf::PDF_A5_MARGIN_LEFT, BaseTcpdf::PDF_A5_MARGIN_TOP, 40);
        $pdf->SetXY(BaseTcpdf::PDF_A5_MARGIN_LEFT, BaseTcpdf::PDF_A5_MARGIN_TOP * 2 + BaseTcpdf::MARGIN_VERTICAL_SMALL);

        // invoice header
        $pdf->setFontStyle(null, '', 11);
        $pdf->Write(0, $this->ts->trans('backend.admin.receipt_reminder.greetings').' ', '', false, 'L', false);
        $pdf->setFontStyle(null, 'B', 11);
        $pdf->Write(0, $subject->getFullName(), '', false, 'L', true);
        $pdf->setFontStyle(null, '', 11);
        $pdf->Ln(BaseTcpdf::MARGIN_VERTICAL_BIG);

        $pdf->Write(0, $this->ts->trans('backend.admin.receipt_reminder.first_paragraph'), '', false, 'L', true);
        $pdf->Ln(BaseTcpdf::MARGIN_VERTICAL_BIG);

        $pdf->Write(0, $this->ts->trans('backend.admin.receipt_reminder.second_paragraph'), '', false, 'L', true);
        $pdf->Ln(BaseTcpdf::MARGIN_VERTICAL_BIG * 2);

        $pdf->Write(0, $this->ts->trans('backend.admin.receipt_reminder.third_paragraph'), '', false, 'L', true);
        $pdf->Ln(BaseTcpdf::MARGIN_VERTICAL_BIG * 0.5);

        $pdf->Write(0, $this->bn, '', false, 'L', true);

//
//        $pdf->Write(0, $this->ts->trans('backend.admin.receipt.pdf.receipt_date').' '.$receipt->getDateString(), '', false, 'L', false);
//        $pdf->SetX($column2Gap);
//        $pdf->Write(0, $subject->getDni(), '', false, 'L', true);
//
//        $pdf->Write(0, $this->ts->trans('Alumne').': '.$receipt->getStudent()->getFullName(), '', false, 'L', false);
//        $pdf->SetX($column2Gap);
//        $pdf->Write(0, $subject->getAddress(), '', false, 'L', true);
//
//        $pdf->SetX($column2Gap);
//        $pdf->Write(0, $subject->getCity()->getCanonicalPostalString(), '', false, 'L', true);

//        $pdf->setFontStyle(null, 'B', 9);
//        $pdf->MultiCell(135, $verticalTableGapSmall, strtoupper($this->ts->trans('backend.admin.invoiceLine.total')), 0, 'R', 0, 0, '', '', true, 0, false, true, 0, 'M');
//        $pdf->MultiCell(15, $verticalTableGapSmall, $this->floatMoneyFormat($receipt->getBaseAmount()), 0, 'R', 0, 1, '', '', true, 0, false, true, 0, 'M');
//        $pdf->setFontStyle(null, '', 9);

        // horitzonal divider
//        $pdf->Ln(BaseTcpdf::MARGIN_VERTICAL_SMALL + 1);
//        $pdf->drawInvoiceLineSeparator($pdf->GetY());
//        $pdf->Ln(BaseTcpdf::MARGIN_VERTICAL_BIG + $verticalTableGapSmall);

        // payment method
//        $pdf->Write(7, $this->ts->trans('backend.admin.invoice.pdf.payment_type').' '.strtoupper($this->ts->trans(StudentPaymentEnum::getEnumArray()[$subject->getPayment()])), '', false, 'L', true);
//        if (StudentPaymentEnum::BANK_ACCOUNT_NUMBER == $subject->getPayment()) {
//            // SEPA direct debit
//            $pdf->Write(7, $this->ts->trans('backend.admin.invoice.pdf.payment.account_number').' '.$subject->getBank()->getAccountNumber(), '', false, 'L', true);
//        } elseif (StudentPaymentEnum::CASH == $subject->getPayment()) {
//            // cash
//            $pdf->Write(7, $this->ts->trans('backend.admin.invoice.pdf.payment.cash'), '', false, 'L', true);
//        } elseif (StudentPaymentEnum::BANK_TRANSFER == $subject->getPayment()) {
//            // bank transfer
//            $pdf->Write(7, $this->ts->trans('backend.admin.invoice.pdf.payment.bank_transfer').' '.$this->ib, '', false, 'L', true);
//        }

        return $pdf;
    }
}
