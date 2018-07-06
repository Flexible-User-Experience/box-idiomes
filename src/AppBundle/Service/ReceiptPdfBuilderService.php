<?php

namespace AppBundle\Service;

use AppBundle\Entity\Receipt;
use AppBundle\Entity\ReceiptLine;
use AppBundle\Enum\StudentPaymentEnum;
use AppBundle\Pdf\BaseTcpdf;
use Symfony\Bundle\FrameworkBundle\Templating\Helper\AssetsHelper;
use Symfony\Bundle\FrameworkBundle\Translation\Translator;
use WhiteOctober\TCPDFBundle\Controller\TCPDFController;

/**
 * Class ReceiptPdfBuilderService.
 *
 * @category Service
 */
class ReceiptPdfBuilderService
{
    /**
     * @var TCPDFController
     */
    private $tcpdf;

    /**
     * @var AssetsHelper
     */
    private $tha;

    /**
     * @var Translator
     */
    private $ts;

    /**
     * @var string project web title
     */
    private $pwt;

    /**
     * @var string boss name
     */
    private $bn;

    /**
     * @var string boss DNI
     */
    private $bd;

    /**
     * @var string boss address
     */
    private $ba;

    /**
     * @var string boss city
     */
    private $bc;

    /**
     * @var string IBAN bussines
     */
    private $ib;

    /**
     * @var string default locale useful in CLI
     */
    private $locale;

    /**
     * @var string mailer URL base
     */
    private $mub;

    /**
     * ReceiptPdfBuilderService constructor.
     *
     * @param TCPDFController $tcpdf
     * @param AssetsHelper    $tha
     * @param Translator      $ts
     * @param string          $pwt
     * @param string          $bn
     * @param string          $bd
     * @param string          $ba
     * @param string          $bc
     * @param string          $ib
     * @param string          $locale
     * @param string          $mub
     */
    public function __construct(TCPDFController $tcpdf, AssetsHelper $tha, Translator $ts, $pwt, $bn, $bd, $ba, $bc, $ib, $locale, $mub)
    {
        $this->tcpdf = $tcpdf;
        $this->tha = $tha;
        $this->ts = $ts;
        $this->pwt = $pwt;
        $this->bn = $bn;
        $this->bd = $bd;
        $this->ba = $ba;
        $this->bc = $bc;
        $this->ib = $ib;
        $this->locale = $locale;
        $this->mub = $mub;
    }

    /**
     * @param Receipt $receipt
     * @param bool    $isCliContext
     *
     * @return \TCPDF
     */
    public function build(Receipt $receipt, $isCliContext = false)
    {
        if ($isCliContext) {
            $this->ts->setLocale($this->locale);
        }

        /** @var BaseTcpdf $pdf */
        $pdf = $this->tcpdf->create($this->tha, $this->ts, $this->mub);

        // set document information
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor($this->pwt);
        $pdf->SetTitle($this->ts->trans('backend.admin.receipt.receipt').' '.$receipt->getReceiptNumber());
        $pdf->SetSubject($this->ts->trans('backend.admin.invoice.detail').' '.$this->ts->trans('backend.admin.invoice.invoice').' '.$receipt->getReceiptNumber());
        // set default font subsetting mode
        $pdf->setFontSubsetting(true);
        // remove default header/footer
        $pdf->setPrintHeader(true);
        $pdf->setPrintFooter(true);
        // set default monospaced font
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
        // set margins
        $pdf->SetMargins(BaseTcpdf::PDF_MARGIN_LEFT, BaseTcpdf::PDF_MARGIN_TOP, BaseTcpdf::PDF_MARGIN_RIGHT);
        $pdf->SetAutoPageBreak(true, BaseTcpdf::PDF_MARGIN_BOTTOM);
        // set image scale factor
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
        // Add start page
        $pdf->AddPage(PDF_PAGE_ORIENTATION, PDF_PAGE_FORMAT, true, true);
        $pdf->setPrintFooter(true);
        $pdf->SetXY(BaseTcpdf::PDF_MARGIN_LEFT, BaseTcpdf::PDF_MARGIN_TOP);

        // invoice header
        $column2Gap = 114;
        $pdf->setFontStyle(null, 'B', 9);
        $pdf->Write(0, $this->ts->trans('backend.admin.receipt.pdf.receipt_data'), '', false, 'L', false);
        $pdf->SetX($column2Gap);
        $pdf->Write(0, $this->ts->trans('backend.admin.invoice.pdf.customer_data'), '', false, 'L', true);
        $pdf->Ln(BaseTcpdf::MARGIN_VERTICAL_SMALL);

        // TODO fetch right customer name (student or parent)
        $pdf->setFontStyle(null, '', 9);

        $pdf->Write(0, $this->ts->trans('backend.admin.receipt.pdf.receipt_number').' '.$receipt->getReceiptNumber(), '', false, 'L', false);
        $pdf->SetX($column2Gap);
        $pdf->Write(0, $receipt->getStudent()->getFullName(), '', false, 'L', true);

        $pdf->Write(0, $this->ts->trans('backend.admin.receipt.pdf.receipt_date').' '.$receipt->getDateString(), '', false, 'L', false);
        $pdf->SetX($column2Gap);
        $pdf->Write(0, $receipt->getStudent()->getDni(), '', false, 'L', true);

//        $pdf->Write(0, $this->bn, '', false, 'L', false);
        $pdf->SetX($column2Gap);
        $pdf->Write(0, $receipt->getStudent()->getAddress(), '', false, 'L', true);

//        $pdf->Write(0, $this->bd, '', false, 'L', false);
        $pdf->SetX($column2Gap);
        $pdf->Write(0, $receipt->getStudent()->getCity()->getCanonicalPostalString(), '', false, 'L', true);

//        $pdf->Write(0, $this->ba, '', false, 'L', true);
//        $pdf->Write(0, $this->bc, '', false, 'L', true);

        // TODO draw SVG
        // $pdf->drawSvg(30, 30, 30, 30);

        // horitzonal divider
        $pdf->Ln(BaseTcpdf::MARGIN_VERTICAL_BIG * 3);
        $pdf->drawInvoiceLineSeparator($pdf->GetY());
        $pdf->Ln(BaseTcpdf::MARGIN_VERTICAL_BIG);

        // receipt table header
        $verticalTableGapSmall = 8;
        $verticalTableGap = 14;
        $pdf->setFontStyle(null, 'B', 9);
        $pdf->Cell(80, $verticalTableGap, $this->ts->trans('backend.admin.invoiceLine.description'), false, 0, 'L');
        $pdf->Cell(15, $verticalTableGap, $this->ts->trans('backend.admin.invoiceLine.units'), false, 0, 'R');
        $pdf->Cell(20, $verticalTableGap, $this->ts->trans('backend.admin.invoiceLine.priceUnit'), false, 0, 'R');
        $pdf->Cell(20, $verticalTableGap, $this->ts->trans('backend.admin.invoiceLine.discount'), false, 0, 'R');
        $pdf->Cell(15, $verticalTableGap, $this->ts->trans('backend.admin.invoiceLine.total'), false, 1, 'R');
        $pdf->setFontStyle(null, '', 9);

        // receipt lines table rows
        /** @var ReceiptLine $line */
        foreach ($receipt->getLines() as $line) {
            // MultiCell($w, $h, $txt, $border=0, $align='J', $fill=0, $ln=1, $x='', $y='', $reseth=true, $stretch=0, $ishtml=false, $autopadding=true, $maxh=0)
            $pdf->MultiCell(80, $verticalTableGapSmall, $line->getDescription(), 0, 'L', 0, 0, '', '', true, 0, false, true, 0, 'M');
            $pdf->MultiCell(15, $verticalTableGapSmall, $this->floatStringFormat($line->getUnits()), 0, 'R', 0, 0, '', '', true, 0, false, true, 0, 'M');
            $pdf->MultiCell(20, $verticalTableGapSmall, $this->floatStringFormat($line->getPriceUnit()), 0, 'R', 0, 0, '', '', true, 0, false, true, 0, 'M');
            $pdf->MultiCell(20, $verticalTableGapSmall, $this->floatStringFormat($line->getDiscount()), 0, 'R', 0, 0, '', '', true, 0, false, true, 0, 'M');
            $pdf->MultiCell(15, $verticalTableGapSmall, $this->floatStringFormat($line->calculateBaseAmount()), 0, 'R', 0, 1, '', '', true, 0, false, true, 0, 'M');
        }

        // horitzonal divider
        $pdf->Ln(BaseTcpdf::MARGIN_VERTICAL_BIG);
        $pdf->drawInvoiceLineSeparator($pdf->GetY());
        $pdf->Ln(BaseTcpdf::MARGIN_VERTICAL_BIG);

        $pdf->setFontStyle(null, 'B', 9);
        $pdf->MultiCell(135, $verticalTableGapSmall, strtoupper($this->ts->trans('backend.admin.invoiceLine.total')), 0, 'R', 0, 0, '', '', true, 0, false, true, 0, 'M');
        $pdf->MultiCell(15, $verticalTableGapSmall, $this->floatMoneyFormat($receipt->getBaseAmount()), 0, 'R', 0, 1, '', '', true, 0, false, true, 0, 'M');
        $pdf->setFontStyle(null, '', 9);

        // horitzonal divider
        $pdf->Ln(BaseTcpdf::MARGIN_VERTICAL_SMALL + 1);
        $pdf->drawInvoiceLineSeparator($pdf->GetY());
        $pdf->Ln(BaseTcpdf::MARGIN_VERTICAL_BIG + $verticalTableGapSmall);

        // payment method
        $pdf->Write(7, $this->ts->trans('backend.admin.invoice.pdf.payment_type').' '.strtoupper($this->ts->trans(StudentPaymentEnum::getEnumArray()[$receipt->getStudent()->getPayment()])), '', false, 'L', true);
        if (StudentPaymentEnum::BANK_ACCOUNT_NUMBER == $receipt->getStudent()->getPayment()) {
            $pdf->Write(7, $this->ts->trans('backend.admin.invoice.pdf.account_number').' '.$this->ib, '', false, 'L', true);
        }

        return $pdf;
    }

    /**
     * @param float $val
     *
     * @return string
     */
    private function floatStringFormat($val)
    {
        return number_format($val, 2, ',', '.');
    }

    /**
     * @param float $val
     *
     * @return string
     */
    private function floatMoneyFormat($val)
    {
        return $this->floatStringFormat($val).' €';
    }
}
