<?php

namespace AppBundle\Pdf;

use AppBundle\Entity\ClassGroup;
use AppBundle\Entity\Student;
use AppBundle\Service\SmartAssetsHelperService;
use Symfony\Bundle\FrameworkBundle\Translation\Translator;
use WhiteOctober\TCPDFBundle\Controller\TCPDFController;

/**
 * Class ClassGroupBuilderPdf.
 *
 * @category Service
 */
class ClassGroupBuilderPdf extends AbstractReceiptInvoiceBuilderPdf
{
    /**
     * ClassGroupBuilderPdf constructor.
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
     * @param ClassGroup      $classGroup
     * @param Student[]|array $students
     *
     * @return \TCPDF
     */
    public function build(ClassGroup $classGroup, $students)
    {
        if ($this->sahs->isCliContext()) {
            $this->ts->setLocale($this->locale);
        }

        /** @var BaseTcpdf $pdf */
        $pdf = $this->tcpdf->create($this->sahs);
        $subject = $classGroup->getName();

        // set document information
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor($this->pwt);
        $pdf->SetTitle($this->ts->trans('backend.admin.invoice.invoice').' '.$classGroup->getId());
        $pdf->SetSubject($this->ts->trans('backend.admin.invoice.detail').' '.$this->ts->trans('backend.admin.invoice.invoice').' '.$classGroup->getId());
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
        $pdf->SetXY(BaseTcpdf::PDF_MARGIN_LEFT, BaseTcpdf::PDF_MARGIN_TOP);

        // gaps
        $column2Gap = 114;
        $verticalTableGapSmall = 8;
        $verticalTableGap = 14;

        // invoice header
        $retainedYForGlobes = $pdf->GetY() - 4;
        $pdf->setFontStyle(null, 'B', 9);
        $pdf->SetX(BaseTcpdf::PDF_MARGIN_LEFT + 4);
        $pdf->Write(0, strtoupper($this->ts->trans('backend.admin.invoice.pdf.invoice_data')), '', false, 'L', false);
        $pdf->SetX($column2Gap);
        $pdf->Write(0, strtoupper($this->ts->trans('backend.admin.invoice.pdf.customer_data')), '', false, 'L', true);
        $pdf->Ln(BaseTcpdf::MARGIN_VERTICAL_SMALL);
        $pdf->setFontStyle(null, '', 9);

        $pdf->SetX(BaseTcpdf::PDF_MARGIN_LEFT + 4);
        $pdf->Write(0, $this->ts->trans('backend.admin.invoice.pdf.invoice_number').' '.$classGroup->getId(), '', false, 'L', false);
        $pdf->SetX($column2Gap);
        $pdf->Write(0, $classGroup->getName(), '', false, 'L', true);

        $pdf->SetX(BaseTcpdf::PDF_MARGIN_LEFT + 4);
        $pdf->Write(0, $this->ts->trans('backend.admin.invoice.pdf.invoice_date').' '.$classGroup->getBook(), '', false, 'L', false);
        $pdf->SetX($column2Gap);
        $pdf->Write(0, $classGroup->getCode(), '', false, 'L', true);

        $pdf->SetXY(BaseTcpdf::PDF_MARGIN_LEFT + 4, $pdf->GetY() + 2);
        $pdf->Write(0, $this->bn, '', false, 'L', false);
        $pdf->SetX($column2Gap);
        $pdf->Write(0, $classGroup->getColor(), '', false, 'L', true);

        // svg globles
        $pdf->drawSvg($this->sahs->getAbsoluteAssetFilePath('/bundles/app/svg/globe-violet.svg'), BaseTcpdf::PDF_MARGIN_LEFT, $retainedYForGlobes, 70, 35);
        $pdf->drawSvg($this->sahs->getAbsoluteAssetFilePath('/bundles/app/svg/globe-blue.svg'), BaseTcpdf::PDF_MARGIN_LEFT + 80, $retainedYForGlobes, 70, 35);

        // horitzonal divider
        $pdf->Ln(BaseTcpdf::MARGIN_VERTICAL_BIG * 3);
        $pdf->drawInvoiceLineSeparator($pdf->GetY());
        $pdf->Ln(BaseTcpdf::MARGIN_VERTICAL_BIG);
//
//        // invoice table header
//        $pdf->setFontStyle(null, 'B', 9);
//        $pdf->Cell(78, $verticalTableGap, $this->ts->trans('backend.admin.invoiceLine.description'), false, 0, 'L');
//        $pdf->Cell(15, $verticalTableGap, $this->ts->trans('backend.admin.invoiceLine.units'), false, 0, 'R');
//        $pdf->Cell(20, $verticalTableGap, $this->ts->trans('backend.admin.invoiceLine.priceUnit'), false, 0, 'R');
//        $pdf->Cell(20, $verticalTableGap, $this->ts->trans('backend.admin.invoiceLine.discount'), false, 0, 'R');
//        $pdf->Cell(17, $verticalTableGap, $this->ts->trans('backend.admin.invoiceLine.total'), false, 1, 'R');
//        $pdf->setFontStyle(null, '', 9);
//
//        // invoice lines table rows
//        /** @var InvoiceLine $line */
//        foreach ($invoice->getLines() as $line) {
//            // MultiCell($w, $h, $txt, $border=0, $align='J', $fill=0, $ln=1, $x='', $y='', $reseth=true, $stretch=0, $ishtml=false, $autopadding=true, $maxh=0)
//            $pdf->MultiCell(78, $verticalTableGapSmall, $line->getDescription(), 0, 'L', 0, 0, '', '', true, 0, false, true, 0, 'M');
//            $pdf->MultiCell(15, $verticalTableGapSmall, $this->floatStringFormat($line->getUnits()), 0, 'R', 0, 0, '', '', true, 0, false, true, 0, 'M');
//            $pdf->MultiCell(20, $verticalTableGapSmall, $this->floatStringFormat($line->getPriceUnit()), 0, 'R', 0, 0, '', '', true, 0, false, true, 0, 'M');
//            $pdf->MultiCell(20, $verticalTableGapSmall, $this->floatStringFormat($line->getDiscount()), 0, 'R', 0, 0, '', '', true, 0, false, true, 0, 'M');
//            $pdf->MultiCell(17, $verticalTableGapSmall, $this->floatStringFormat($line->getTotal()), 0, 'R', 0, 1, '', '', true, 0, false, true, 0, 'M');
//        }
//        $pdf->MultiCell(150, $verticalTableGapSmall, $this->ts->trans('Alumne').': '.$invoice->getStudent()->getFullName(), 0, 'L', 0, 1, '', '', true, 0, false, true, 0, 'M');
//
//        // horitzonal divider
//        $pdf->Ln(BaseTcpdf::MARGIN_VERTICAL_BIG);
//        $pdf->drawInvoiceLineSeparator($pdf->GetY());
//        $pdf->Ln(BaseTcpdf::MARGIN_VERTICAL_BIG);

        return $pdf;
    }
}
