<?php

namespace AppBundle\Pdf;

use AppBundle\Service\SmartAssetsHelperService;
use Symfony\Bundle\FrameworkBundle\Translation\Translator;
use WhiteOctober\TCPDFBundle\Controller\TCPDFController;

/**
 * Abstract class AbstractReceiptInvoiceBuilderPdf.
 *
 * @category Service
 */
abstract class AbstractReceiptInvoiceBuilderPdf
{
    /**
     * @var TCPDFController
     */
    protected $tcpdf;

    /**
     * @var SmartAssetsHelperService
     */
    protected $sahs;

    /**
     * @var Translator
     */
    protected $ts;

    /**
     * @var string project web title
     */
    protected $pwt;

    /**
     * @var string boss name
     */
    protected $bn;

    /**
     * @var string boss DNI
     */
    protected $bd;

    /**
     * @var string boss address
     */
    protected $ba;

    /**
     * @var string boss city
     */
    protected $bc;

    /**
     * @var string IBAN bussines
     */
    protected $ib;

    /**
     * @var string default locale useful in CLI
     */
    protected $locale;

    /**
     * AbstractReceiptInvoiceBuilderPdf constructor.
     *
     * @param TCPDFController          $tcpdf
     * @param SmartAssetsHelperService $sahs
     * @param Translator               $ts
     * @param string                   $pwt
     * @param string                   $bn
     * @param string                   $bd
     * @param string                   $ba
     * @param string                   $bc
     * @param string                   $ib
     * @param string                   $locale
     */
    public function __construct(TCPDFController $tcpdf, SmartAssetsHelperService $sahs, Translator $ts, $pwt, $bn, $bd, $ba, $bc, $ib, $locale)
    {
        $this->tcpdf = $tcpdf;
        $this->sahs = $sahs;
        $this->ts = $ts;
        $this->pwt = $pwt;
        $this->bn = $bn;
        $this->bd = $bd;
        $this->ba = $ba;
        $this->bc = $bc;
        $this->ib = $ib;
        $this->locale = $locale;
    }

    /**
     * @param float $val
     *
     * @return string
     */
    protected function floatStringFormat($val)
    {
        return number_format($val, 2, ',', '.');
    }

    /**
     * @param float $val
     *
     * @return string
     */
    protected function floatMoneyFormat($val)
    {
        return $this->floatStringFormat($val).' €';
    }

    /**
     * Convert a hexa decimal color code to its RGB equivalent.
     *
     * @param string $hexStr (hexadecimal color value)
     * @param bool $returnAsString (if set true, returns the value separated by the separator character. Otherwise returns associative array)
     * @param string $seperator (to separate RGB values. Applicable only if second parameter is true.)
     *
     * @return array|string|bool (depending on second parameter. Returns False if invalid hex color value)
     */
    protected function hex2RGBarray($hexStr, $returnAsString = false, $seperator = ',')
    {
        $hexStr = preg_replace('/[^0-9A-Fa-f]/', '', $hexStr); // Gets a proper hex string
        $rgbArray = array();
        if (6 == strlen($hexStr)) { //If a proper hex code, convert using bitwise operation. No overhead... faster
            $colorVal = hexdec($hexStr);
            $rgbArray[] = 0xFF & ($colorVal >> 0x10);
            $rgbArray[] = 0xFF & ($colorVal >> 0x8);
            $rgbArray[] = 0xFF & $colorVal;
        } elseif (3 == strlen($hexStr)) { //if shorthand notation, need some string manipulations
            $rgbArray[] = hexdec(str_repeat(substr($hexStr, 0, 1), 2));
            $rgbArray[] = hexdec(str_repeat(substr($hexStr, 1, 1), 2));
            $rgbArray[] = hexdec(str_repeat(substr($hexStr, 2, 1), 2));
        } else {
            return false; //Invalid hex color code
        }

        return $returnAsString ? implode($seperator, $rgbArray) : $rgbArray;
    }
}
