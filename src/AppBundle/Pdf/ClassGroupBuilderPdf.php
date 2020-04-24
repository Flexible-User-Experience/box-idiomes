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
        $verticalTableGapSmall = 6;
        $verticalTableGap = 10;

        // today
        $today = new \DateTimeImmutable();

        // invoice header
        $retainedYForGlobes = $pdf->GetY() - 4;
        $pdf->setFontStyle(null, 'B', 9);
        $pdf->SetX(BaseTcpdf::PDF_MARGIN_LEFT + 4);
        $pdf->Write(0, strtoupper($this->ts->trans('backend.admin.class_group.pdf.title')), '', false, 'L', false);
        $pdf->SetX($column2Gap);
        $pdf->Write(0, $today->format('d/m/Y').'    ', '', false, 'R', true);
        $pdf->Ln(BaseTcpdf::MARGIN_VERTICAL_SMALL);
        $pdf->setFontStyle(null, '', 9);

        $pdf->SetX(BaseTcpdf::PDF_MARGIN_LEFT + 4);
        $pdf->Write(0, $this->ts->trans('backend.admin.class_group.pdf.group').' '.$classGroup->getCode(), '', false, 'L', false);
        $pdf->SetX($column2Gap);
        $pdf->Write(0, $this->ts->trans('backend.admin.class_group.pdf.total').' '.count($students).'    ', '', false, 'R', true);

        if ($classGroup->getName()) {
            $pdf->SetX(BaseTcpdf::PDF_MARGIN_LEFT + 4);
            $pdf->Write(0, $this->ts->trans('backend.admin.class_group.name').': '.$classGroup->getName(), '', false, 'L', false);
            $pdf->SetX($column2Gap);
            $pdf->Write(0, 'Color    '/* TODO */, '', false, 'R', true);
        } else {
            $pdf->SetX(BaseTcpdf::PDF_MARGIN_LEFT + 4);
            $pdf->Write(0, '', '', false, 'L', false);
            $pdf->SetX($column2Gap);
            $pdf->Write(0, 'Color    '/* TODO */, '', false, 'R', true);
        }

        if ($classGroup->getBook()) {
            $pdf->SetX(BaseTcpdf::PDF_MARGIN_LEFT + 4);
            $pdf->Write(0, $this->ts->trans('backend.admin.class_group.book').': '.$classGroup->getBook(), '', false, 'L', true);
        }

        // svg globles
        $pdf->drawSvg($this->sahs->getAbsoluteAssetFilePath('/bundles/app/svg/globe-violet.svg'), BaseTcpdf::PDF_MARGIN_LEFT, $retainedYForGlobes, 70, 35);
        $pdf->drawSvg($this->sahs->getAbsoluteAssetFilePath('/bundles/app/svg/globe-blue.svg'), BaseTcpdf::PDF_MARGIN_LEFT + 80, $retainedYForGlobes, 70, 35);

        // horitzonal divider
        $pdf->Ln(BaseTcpdf::MARGIN_VERTICAL_BIG * 3);
        $pdf->drawInvoiceLineSeparator($pdf->GetY());
        $pdf->Ln(BaseTcpdf::MARGIN_VERTICAL_BIG);

        if (0 == count($students)) {
            // students table header
            $pdf->setFontStyle(null, 'B', 9);
            $pdf->Cell(78, $verticalTableGap, $this->ts->trans('backend.admin.student.name'), false, 0, 'L');
            $pdf->Cell(72, $verticalTableGap, $this->ts->trans('backend.admin.student.email'), false, 1, 'R');
            $pdf->setFontStyle(null, '', 9);
            // students lines table rows
            /** @var Student $student */
            foreach ($students as $student) {
                $pdf->MultiCell(78, $verticalTableGapSmall, $student->getFullCanonicalName(), 0, 'L', 0, 0, '', '', true, 0, false, true, 0, 'M');
                $pdf->MultiCell(72, $verticalTableGapSmall, $student->getEmail(), 0, 'R', 0, 1, '', '', true, 0, false, true, 0, 'M');
            }
        } else {
            $pdf->Cell(150, $verticalTableGap, $this->ts->trans('backend.admin.class_group.emails_generator.flash_warning'), false, 1, 'L');
        }

        // final horitzonal divider
        $pdf->Ln(BaseTcpdf::MARGIN_VERTICAL_BIG);
        $pdf->drawInvoiceLineSeparator($pdf->GetY());
        $pdf->Ln(BaseTcpdf::MARGIN_VERTICAL_BIG);

        return $pdf;
    }
}
