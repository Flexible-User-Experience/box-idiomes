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
        $pdf->setFontStyle(null, '', 11);
        // Description
        $pdf->Write(0, $this->ts->trans('backend.admin.pdf.description1'), '', false, 'L', true);
        $pdf->Ln(BaseTcpdf::MARGIN_VERTICAL_SMALL);
        $pdf->Write(0, $this->ts->trans('backend.admin.pdf.description2'), '', false, 'L', true);
        $pdf->Ln(BaseTcpdf::MARGIN_VERTICAL_BIG);
        // Contact name
        $pdf->Write(0, $this->ts->trans('backend.admin.pdf.contact_name', array('%contact_name%' => ($student->getContactName() ? $student->getContactName() : '____________________________________'))), '', false, 'L', true);
        $pdf->Ln(BaseTcpdf::MARGIN_VERTICAL_SMALL);
        $pdf->Write(0, $this->ts->trans('backend.admin.pdf.contact_dni', array('%contact_dni%' => ($student->getContactDni() ? $student->getContactDni() : '____________________________________'))), '', false, 'L', true);
        $pdf->Ln(BaseTcpdf::MARGIN_VERTICAL_SMALL);
        $pdf->SetX(55);
        $pdf->Rect(51, $pdf->GetY() + 1, 3, 3);
        $pdf->MultiCell(125, 0, $this->ts->trans('backend.admin.pdf.autortization1', array('%student_name%' => $student->getName(), '%years_old%' => $student->getYearsOld())), 0, 'L', false, 1);
        $pdf->Ln(BaseTcpdf::MARGIN_VERTICAL_SMALL);
        $pdf->SetX(55);
        $pdf->Rect(51, $pdf->GetY() + 1, 3, 3);
        $pdf->MultiCell(125, 0, $this->ts->trans('backend.admin.pdf.autortization2', array('%student_name%' => $student->getName(), '%years_old%' => $student->getYearsOld())), 0, 'L', false, 1);
//        $pdf->Write(0, $this->ts->trans('backend.admin.pdf.autortization2'), '', false, 'L', true);
        $pdf->Ln(BaseTcpdf::MARGIN_VERTICAL_BIG);
        // Registration date
        $today = new \DateTime();
        $pdf->Write(0, $this->ts->trans('backend.admin.pdf.registration_date', array('%day%' => $today->format('j'), '%month%' => $today->format('F'), '%year%' => $today->format('Y'))), '', false, 'L', true);
        $pdf->Ln(BaseTcpdf::MARGIN_VERTICAL_BIG);
        $pdf->Write(0, $this->ts->trans('backend.admin.pdf.sign'), '', false, 'L', true);

        return $pdf;
    }
}
