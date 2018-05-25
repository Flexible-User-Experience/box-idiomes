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
     * @var string public web title
     */
    private $pwt;

    /**
     * @var string boss name
     */
    private $bn;

    /**
     * Methods.
     */

    /**
     * SepaAgreementPdfService constructor.
     *
     * @param TCPDFController $tcpdf
     * @param AssetsHelper    $tha
     * @param Translator      $ts
     * @param string          $pwt
     * @param string          $bn
     */
    public function __construct(TCPDFController $tcpdf, AssetsHelper $tha, Translator $ts, $pwt, $bn)
    {
        $this->tcpdf = $tcpdf;
        $this->tha = $tha;
        $this->ts = $ts;
        $this->pwt = $pwt;
        $this->bn = $bn;
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

        $maxCellWidth = BaseTcpdf::PDF_WIDTH - BaseTcpdf::PDF_MARGIN_LEFT - BaseTcpdf::PDF_MARGIN_RIGHT;

        // set document information
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor($this->pwt);
        $pdf->SetTitle($this->ts->trans('backend.admin.sepa_agreement'));
        $pdf->SetSubject($this->ts->trans('backend.admin.sepa_agreement'));
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
        // table
        $pdf->Ln(BaseTcpdf::MARGIN_VERTICAL_BIG);
        $pdf->setCellPaddings(2, 1, 0, 0);
        $pdf->setCellMargins(1, 0, 1, 0);
        // contact
        $subject = $student;
        if ($student->getParent()) {
            $subject = $student->getParent();
        }
        $pdf->MultiCell($maxCellWidth, 12.5, '<strong>'.$this->ts->trans('backend.admin.sepaagreement.contact_name').'</strong><br>'.$subject->getName().' '.$subject->getSurname(), 1, 'L', false, 1, '', '', true, 0, true, true, 0, 'T', false);
        $pdf->MultiCell($maxCellWidth, 12.5, '<strong>'.$this->ts->trans('backend.admin.sepaagreement.contact_dni').'</strong><br>'.$subject->getDni(), 1, 'L', false, 1, '', '', true, 0, true, true, 0, 'T', false);
        $pdf->MultiCell($maxCellWidth, 12.5, '<strong>'.$this->ts->trans('backend.admin.sepaagreement.parent_address').'</strong><br>'.$subject->getAddress(), 1, 'L', false, 1, '', '', true, 0, true, true, 0, 'T', false);
        $pdf->MultiCell($maxCellWidth, 12.5, '<strong>'.$this->ts->trans('backend.admin.sepaagreement.contact_phone').'</strong><br>'.$subject->getPhone(), 1, 'L', false, 1, '', '', true, 0, true, true, 0, 'T', false);
        $pdf->MultiCell($maxCellWidth, 12.5, '<strong>'.$this->ts->trans('backend.admin.sepaagreement.bank_name').'</strong><br>'.$subject->getBank()->getName(), 1, 'L', false, 1, '', '', true, 0, true, true, 0, 'T', false);
        // iban
        $pdf->MultiCell($maxCellWidth, 7, '<strong>'.$this->ts->trans('backend.admin.sepaagreement.bank_account').'</strong>', 1, 'L', false, 1, '', '', true, 0, true, true, 0, 'T', false);
        $pdf->setCellMargins(1, 0, 0, 0);
        $pdf->MultiCell(25, 7, $subject->getBank()->getBAN1part(), 1, 'C', false, 0, '', '', true, 0, true, true, 0, 'T', false);
        $pdf->setCellMargins(0, 0, 0, 0);
        $pdf->MultiCell(25, 7, $subject->getBank()->getBAN2part(), 1, 'C', false, 0, '', '', true, 0, true, true, 0, 'T', false);
        $pdf->MultiCell(25, 7, $subject->getBank()->getBAN3part(), 1, 'C', false, 0, '', '', true, 0, true, true, 0, 'T', false);
        $pdf->MultiCell(25, 7, $subject->getBank()->getBAN4part(), 1, 'C', false, 0, '', '', true, 0, true, true, 0, 'T', false);
        $pdf->MultiCell(25, 7, $subject->getBank()->getBAN5part(), 1, 'C', false, 0, '', '', true, 0, true, true, 0, 'T', false);
        $pdf->MultiCell(25, 7, $subject->getBank()->getBAN6part(), 1, 'C', false, 1, '', '', true, 0, true, true, 0, 'T', false);
        $pdf->setCellMargins(1, 0, 1, 0);
        $pdf->setCellPaddings(0, 0, 0, 0);
        // description legal
        $pdf->Ln(BaseTcpdf::MARGIN_VERTICAL_BIG);
        $pdf->Write(0, $this->ts->trans('backend.admin.sepaagreement.end_text'), '', false, 'L', true);
        // signs
        $pdf->Ln(BaseTcpdf::MARGIN_VERTICAL_BIG);
        $pdf->MultiCell($maxCellWidth / 2, 7, '<strong>'.$this->bn.'</strong>', 0, 'L', false, 0, '', '', true, 0, true, true, 0, 'T', false);
        $pdf->setCellPaddings(0, 0, 2, 0);
        $pdf->MultiCell($maxCellWidth / 2, 7, '<strong>'.$this->ts->trans('backend.admin.imagerigths.sign').'</strong>', 0, 'R', false, 1, '', '', true, 0, true, true, 0, 'T', false);

        return $pdf;
    }
}
