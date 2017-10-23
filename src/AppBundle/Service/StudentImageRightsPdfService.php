<?php

namespace AppBundle\Service;

use AppBundle\Entity\Student;
use AppBundle\Pdf\BaseTcpdf;
use Symfony\Bundle\FrameworkBundle\Translation\Translator;
use WhiteOctober\TCPDFBundle\Controller\TCPDFController;
use Symfony\Bundle\FrameworkBundle\Templating\Helper\AssetsHelper;

/**
 * Class StudentImageRightsPdfService.
 */
class StudentImageRightsPdfService
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
     * StudentImageRightsPdfService constructor.
     *
     * @param TCPDFController $tcpdf
     * @param AssetsHelper    $tha
     * @param Translator      $ts
     * @param string          $pwt
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
        $pdf->SetTitle($this->ts->trans('drets imatges'));
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

        // Add a page
        $pdf->AddPage(PDF_PAGE_ORIENTATION, PDF_PAGE_FORMAT, true, true);
        $pdf->setPrintFooter(true);

        $pdf->SetXY(BaseTcpdf::PDF_MARGIN_LEFT, BaseTcpdf::PDF_MARGIN_TOP);
//        $pdf->setBlackText();
//        $pdf->setFontStyle(null, 'B', 11);
//        $pdf->Write(0, $this->pwt, '', false, 'L', true);
//        $pdf->Write(0, $student->getFullName(), '', false, 'L', true);
//        $pdf->Ln(2);
        $pdf->setFontStyle(null, '', 9);
        // Description
        $pdf->Write(0, $this->ts->trans('backend.admin.pdf.description1'), '', false, 'L', true);
        $pdf->Ln(2);
        $pdf->Write(0, $this->ts->trans('backend.admin.pdf.description2'), '', false, 'L', true);
        $pdf->Ln(6);
        // Contact name
        $pdf->Write(0, $this->ts->trans('backend.admin.pdf.contact_name', array('%contact_name%' => ($student->getContactName() ? $student->getContactName() : '____________________________________'))), '', false, 'L', true);
        $pdf->Ln(2);

        return $pdf;
    }
}
