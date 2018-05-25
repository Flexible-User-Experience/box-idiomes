<?php

namespace AppBundle\Service;

use AppBundle\Entity\Invoice;
use AppBundle\Pdf\BaseTcpdf;
use Symfony\Bundle\FrameworkBundle\Templating\Helper\AssetsHelper;
use Symfony\Bundle\FrameworkBundle\Translation\Translator;
use WhiteOctober\TCPDFBundle\Controller\TCPDFController;

/**
 * Class InvoicePdfBuilderService.
 *
 * @category Service
 */
class InvoicePdfBuilderService
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
     * InvoicePdfBuilderService constructor.
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
     */
    public function __construct(TCPDFController $tcpdf, AssetsHelper $tha, Translator $ts, $pwt, $bn, $bd, $ba, $bc, $ib)
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
    }

    /**
     * @param Invoice $invoice
     *
     * @return \TCPDF
     */
    public function build(Invoice $invoice)
    {
        /** @var BaseTcpdf $pdf */
        $pdf = $this->tcpdf->create($this->tha, $this->ts);

        // $maxCellWidth = BaseTcpdf::PDF_WIDTH - BaseTcpdf::PDF_MARGIN_LEFT - BaseTcpdf::PDF_MARGIN_RIGHT;

        // set document information
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor($this->pwt);
        $pdf->SetTitle($this->ts->trans('backend.admin.invoice.invoice').' '.$invoice->getInvoiceNumber());
        $pdf->SetSubject($this->ts->trans('backend.admin.invoice.detail').' '.$this->ts->trans('backend.admin.invoice.invoice').' '.$invoice->getInvoiceNumber());
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
        $pdf->Write(0, $this->ts->trans('backend.admin.invoice.pdf.invoice_data'), '', false, 'L', false);
        $pdf->SetX($column2Gap);
        $pdf->Write(0, $this->ts->trans('backend.admin.invoice.pdf.customer_data'), '', false, 'L', true);
        $pdf->Ln(BaseTcpdf::MARGIN_VERTICAL_SMALL);

        // TODO fetch right customer name (student or parent)
        $pdf->setFontStyle(null, '', 9);

        $pdf->Write(0, $this->ts->trans('backend.admin.invoice.pdf.invoice_number').' '.$invoice->getInvoiceNumber(), '', false, 'L', false);
        $pdf->SetX($column2Gap);
        $pdf->Write(0, $invoice->getStudent()->getFullName(), '', false, 'L', true);

        $pdf->Write(0, $this->ts->trans('backend.admin.invoice.pdf.invoice_date').' '.$invoice->getDateString(), '', false, 'L', false);
        $pdf->SetX($column2Gap);
        $pdf->Write(0, $invoice->getStudent()->getDni(), '', false, 'L', true);

        $pdf->Write(0, $this->bn, '', false, 'L', false);
        $pdf->SetX($column2Gap);
        $pdf->Write(0, $invoice->getStudent()->getAddress(), '', false, 'L', true);

        $pdf->Write(0, $this->bd, '', false, 'L', false);
        $pdf->SetX($column2Gap);
        $pdf->Write(0, $invoice->getStudent()->getCity()->getCanonicalPostalString(), '', false, 'L', true);

//        $pdf->Ln(BaseTcpdf::MARGIN_VERTICAL_SMALL);
        $pdf->Write(0, $this->ba, '', false, 'L', true);
//        $pdf->Ln(BaseTcpdf::MARGIN_VERTICAL_SMALL);
        $pdf->Write(0, $this->bc, '', false, 'L', true);
//        $pdf->Ln(BaseTcpdf::MARGIN_VERTICAL_SMALL);

        return $pdf;
    }
}
