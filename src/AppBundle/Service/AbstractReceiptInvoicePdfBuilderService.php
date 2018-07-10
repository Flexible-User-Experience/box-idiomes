<?php

namespace AppBundle\Service;

use Symfony\Bundle\FrameworkBundle\Translation\Translator;
use WhiteOctober\TCPDFBundle\Controller\TCPDFController;

/**
 * Abstract class AbstractReceiptInvoicePdfBuilderService.
 *
 * @category Service
 */
abstract class AbstractReceiptInvoicePdfBuilderService
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
     * AbstractReceiptInvoicePdfBuilderService constructor.
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
}
