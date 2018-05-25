<?php

namespace AppBundle\Service;

use AppBundle\Entity\Student;
use AppBundle\Pdf\BaseTcpdf;
use IntlDateFormatter;
use Symfony\Bundle\FrameworkBundle\Translation\Translator;
use WhiteOctober\TCPDFBundle\Controller\TCPDFController;
use Symfony\Bundle\FrameworkBundle\Templating\Helper\AssetsHelper;

/**
 * Class StudentImageRightsPdfService.
 *
 * @category Service
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
     * Methods.
     */

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
        $pdf->SetTitle($this->ts->trans('backend.admin.imagerigths.pdf_metadata_title'));
        $pdf->SetSubject($this->ts->trans('backend.admin.imagerigths.pdf_metadata_subject'));
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
        // Description
        $pdf->Write(0, $this->ts->trans('backend.admin.imagerigths.description1'), '', false, 'L', true);
        $pdf->Ln(BaseTcpdf::MARGIN_VERTICAL_SMALL);
        $pdf->Write(0, $this->ts->trans('backend.admin.imagerigths.description2'), '', false, 'L', true);
        $pdf->Ln(BaseTcpdf::MARGIN_VERTICAL_BIG);
        // Contact name
        $contactName = $student->getName().' '.$student->getSurname();
        $contactDni = $student->getDni();
        if ($student->getParent()) {
            $contactName = $student->getParent()->getName().' '.$student->getParent()->getSurname();
            $contactDni = $student->getParent()->getDni();
        }
        $pdf->Write(0, $this->ts->trans(($student->getParent() ? 'backend.admin.imagerigths.contact_name_with_parent' : 'backend.admin.imagerigths.contact_name_without_parent'), array('%name%' => $contactName)), '', false, 'L', true);
        $pdf->Ln(BaseTcpdf::MARGIN_VERTICAL_SMALL);
        $pdf->Write(0, $this->ts->trans('backend.admin.imagerigths.contact_dni', array('%contact_dni%' => $contactDni)), '', false, 'L', true);
        $pdf->Ln(BaseTcpdf::MARGIN_VERTICAL_SMALL);
        $pdf->SetX(55);
        $pdf->Rect(51, $pdf->GetY() + 1, 3, 3);
        $pdf->MultiCell(125, 0, $this->ts->trans('backend.admin.imagerigths.autortization1', array('%student_name%' => $student->getName(), '%years_old%' => $student->getYearsOld())), 0, 'L', false, 1);
        $pdf->Ln(BaseTcpdf::MARGIN_VERTICAL_SMALL);
        $pdf->SetX(55);
        $pdf->Rect(51, $pdf->GetY() + 1, 3, 3);
        $pdf->MultiCell(125, 0, $this->ts->trans('backend.admin.imagerigths.autortization2', array('%student_name%' => $student->getName(), '%years_old%' => $student->getYearsOld())), 0, 'L', false, 1);
        $pdf->Ln(BaseTcpdf::MARGIN_VERTICAL_BIG);
        // Registration date
        $today = new \DateTime();
        $df = new IntlDateFormatter('ca_ES', IntlDateFormatter::SHORT, IntlDateFormatter::NONE);
        $df->setPattern('MMMM');
        $pdf->Write(0, $this->ts->trans('backend.admin.imagerigths.registration_date', array('%day%' => $today->format('j'), '%month%' => $df->format($today), '%year%' => $today->format('Y'))), '', false, 'L', true);
        $pdf->Ln(BaseTcpdf::MARGIN_VERTICAL_BIG);
        $pdf->setFontStyle(null, 'B', 11);
        $pdf->Write(0, $this->ts->trans('backend.admin.imagerigths.sign'), '', false, 'L', true);

        return $pdf;
    }
}
