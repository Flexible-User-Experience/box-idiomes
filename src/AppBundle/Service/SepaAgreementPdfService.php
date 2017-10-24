<?php

namespace AppBundle\Service;

use AppBundle\Entity\Student;
use AppBundle\Pdf\BaseTcpdf;
use Symfony\Bundle\FrameworkBundle\Translation\Translator;
use WhiteOctober\TCPDFBundle\Controller\TCPDFController;
use Symfony\Bundle\FrameworkBundle\Templating\Helper\AssetsHelper;

/**
 * Class SepaAgreementPdfService.
 *
 * @category Service
 *
 * @author   Wils Iglesias <wiglesias83@gmail.com>
 */
class SepaAgreementPdfService
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
     * @var string
     */
    private $pwt;

    /**
     * SepaAgreementPdfService constructor.
     *
     * @param TCPDFController $tcpdf
     * @param AssetsHelper    $tha
     * @param Translator      $ts
     * @param $pwt
     */
    public function __construct(TCPDFController $tcpdf, AssetsHelper $tha, Translator $ts, $pwt)
    {
        $this->tcpdf = $tcpdf;
        $this->tha = $tha;
        $this->ts = $ts;
        $this->pwt = $pwt;
    }

    /**
     * @param Student $student
     *
     * @return \TCPDF
     */
    public function build(Student $student)
    {
        /** @var BaseTcpdf $pdf */
        $pdf = $this->tcpdf->create($this->tha, $this->ts);

        // set document information
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor($this->pwt);
        $pdf->SetTitle($this->ts->trans('Autorització SEPA'));
        $pdf->SetSubject($this->ts->trans('descripció'));
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
        $pdf->setFontStyle(null, '', 11);
        // description
        $pdf->Write(0, $this->ts->trans('backend.admin.sepaagreement.first_text'), '', false, 'L', true);
        $pdf->Ln(BaseTcpdf::MARGIN_VERTICAL_SMALL);
        $pdf->Write(0, $this->ts->trans('backend.admin.sepaagreement.text1'), '', false, 'L', true);
        $pdf->Ln(BaseTcpdf::MARGIN_VERTICAL_SMALL);
        $pdf->Write(0, $this->ts->trans('backend.admin.sepaagreement.text2'), '', false, 'L', true);
        $pdf->Ln(BaseTcpdf::MARGIN_VERTICAL_SMALL);
        $pdf->Write(0, $this->ts->trans('backend.admin.sepaagreement.text3'), '', false, 'L', true);
        $pdf->Ln(BaseTcpdf::MARGIN_VERTICAL_SMALL);
        $pdf->setCellPaddings(2, 1, 0, 0);
        $pdf->setCellMargins(1, 0, 1, 0);
        $pdf->MultiCell(BaseTcpdf::PDF_WIDTH - BaseTcpdf::PDF_MARGIN_LEFT - BaseTcpdf::PDF_MARGIN_RIGHT, 12.5, '<strong>'.$this->ts->trans('backend.admin.sepaagreement.contact_name').'</strong><br>'.$student->getContactName(), 1, 'L', false, 1, '', '', true, 0, true, true, 0, 'T', false);
//        $pdf->Cell(BaseTcpdf::PDF_WIDTH - BaseTcpdf::PDF_MARGIN_LEFT - BaseTcpdf::PDF_MARGIN_RIGHT, 19, 'hit me', 1, 1, 'L', false, '', 0, false, 'T', 'T');

        return $pdf;
    }
}
