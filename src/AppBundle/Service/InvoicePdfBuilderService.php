<?php

namespace AppBundle\Service;

use Symfony\Bundle\FrameworkBundle\Templating\Helper\AssetsHelper;
use Symfony\Bundle\FrameworkBundle\Translation\Translator;
use WhiteOctober\TCPDFBundle\Controller\TCPDFController;

/**
 * Class InvoicePdfBuilderService
 *
 * @package AppBundle\Service
 *
 * @author  Wils Iglesias <wiglesias83@gmail.com>
 */
class InvoicePdfBuilderService
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
     * InvoicePdfBuilderService constructor.
     * @param TCPDFController $tcpdf
     * @param AssetsHelper $tha
     * @param Translator $ts
     */
    public function __construct(TCPDFController $tcpdf, AssetsHelper $tha, Translator $ts)
    {
        $this->tcpdf = $tcpdf;
        $this->tha = $tha;
        $this->ts = $ts;
    }
}
