<?php

namespace AppBundle\Pdf;

use AppBundle\Service\SmartAssetsHelperService;

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
    const PDF_A5_MARGIN_LEFT = 25;
    const PDF_A5_MARGIN_RIGHT = 25;
    const PDF_A5_MARGIN_TOP = 25;
    const PDF_A5_MARGIN_BOTTOM = 25;
    const MARGIN_VERTICAL_SMALL = 3;
    const MARGIN_VERTICAL_BIG = 8;

    /**
     * @var SmartAssetsHelperService
     */
    private $sahs;

    /**
     * Methods.
     */

    /**
     * BaseTcpdf constructor.
     *
     * @param SmartAssetsHelperService $sahs
     */
    public function __construct(SmartAssetsHelperService $sahs)
    {
        parent::__construct();
        $this->sahs = $sahs;
    }

    /**
     * Page header.
     */
    public function header()
    {
        // logo
        $this->Image($this->sahs->getAbsoluteAssetFilePath('/bundles/app/img/logo-pdf.png'), 75, 20, 60);
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
     * @param string       $file
     * @param float|string $x
     * @param float|string $y
     * @param float|int    $w
     * @param float|int    $h
     */
    public function drawSvg($file, $x = '', $y = '', $w = 0, $h = 0)
    {
        $this->ImageSVG($file, $x, $y, $w);
    }
}
