<?php

namespace AppBundle\Pdf;

use Symfony\Bundle\FrameworkBundle\Templating\Helper\AssetsHelper;
use Symfony\Bundle\FrameworkBundle\Translation\Translator;

/**
 * Class BaseTcpdf.
 *
 * @category Pdf
 */
class BaseTcpdf extends \TCPDF
{
    const PDF_WIDTH = 210;
    const PDF_MARGIN_LEFT = 30;
    const PDF_MARGIN_RIGHT = 30;
    const PDF_MARGIN_TOP = 70;
    const PDF_MARGIN_BOTTOM = 25;
    const MARGIN_VERTICAL_SMALL = 3;
    const MARGIN_VERTICAL_BIG = 8;

    /**
     * @var AssetsHelper
     */
    private $ahs;

    /**
     * @var Translator
     */
    private $ts;

    /**
     * @var string Kernel root dir
     */
    private $krd;

    /**
     * @var string absolute web dir path
     */
    private $awpd;

    /**
     * BaseTcpdf constructor.
     *
     * @param AssetsHelper $ahs
     * @param Translator   $ts
     * @param string       $krd
     */
    public function __construct(AssetsHelper $ahs, Translator $ts, $krd)
    {
        parent::__construct();
        $this->ahs = $ahs;
        $this->ts = $ts;
        $this->krd = $krd;
        $this->awpd = realpath($this->krd.'/../web');
    }

    /**
     * Page header.
     */
    public function header()
    {
        // logo
        if ('cli' === php_sapi_name()) {
            $this->Image($this->awpd.'/bundles/app/img/logo-pdf.png', 75, 20, 60);
        } else {
            $this->Image($this->ahs->getUrl('/bundles/app/img/logo-pdf.png'), 75, 20, 60);
        }
        $this->SetXY(self::PDF_MARGIN_LEFT, 11);
        $this->setFontStyle(null, 'I', 8);
    }

    /**
     * @param string $font
     * @param string $style
     * @param int    $size
     */
    public function setFontStyle($font = 'dejavusans', $style = '', $size = 12)
    {
        // dejavusans is a UTF-8 Unicode font, if you only need to
        // print standard ASCII chars, you can use core fonts like
        // helvetica or times to reduce file size.
        $this->SetFont($font, $style, $size, '', true);
    }

    /**
     * @param float $y
     */
    public function drawInvoiceLineSeparator($y)
    {
        $this->Line(
            self::PDF_MARGIN_LEFT,
            $y,
            self::PDF_WIDTH - self::PDF_MARGIN_RIGHT,
            $y,
            array(
                'width' => 5,
                'cap' => 'butt',
                'join' => 'miter',
                'dash' => 0,
                'color' => array(125, 20, 126),
            )
        );
    }

    /**
     * @param float $x
     * @param float $y
     * @param float $w
     * @param float $h
     */
    public function drawSvg($x, $y, $w, $h)
    {
        $this->ImageSVG($this->ahs->getUrl('/bundles/app/svg/compass.svg'), $x, $y, $w, $h, '', '', '', 0, false);
    }
}
