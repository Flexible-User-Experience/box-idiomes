<?php

namespace AppBundle\Manager;

use AppBundle\Repository\EventRepository;
use AppBundle\Repository\StudentRepository;
use AppBundle\Repository\TariffRepository;
use Doctrine\ORM\EntityManager;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * Abstract class AbstractGenerateReceiptInvoiceFormManager.
 *
 * @category Manager
 */
abstract class AbstractGenerateReceiptInvoiceFormManager
{
    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @var KernelInterface
     */
    protected $kernel;

    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @var TranslatorInterface
     */
    protected $ts;

    /**
     * @var StudentRepository
     */
    protected $sr;

    /**
     * @var EventRepository
     */
    protected $er;

    /**
     * @var TariffRepository
     */
    protected $tr;

    /**
     * Methods.
     */

    /**
     * AbstractGenerateReceiptInvoiceFormManager constructor.
     *
     * @param LoggerInterface     $logger
     * @param KernelInterface     $kernel
     * @param EntityManager       $em
     * @param TranslatorInterface $ts
     * @param StudentRepository   $sr
     * @param EventRepository     $er
     * @param TariffRepository    $tr
     */
    public function __construct(LoggerInterface $logger, KernelInterface $kernel, EntityManager $em, TranslatorInterface $ts, StudentRepository $sr, EventRepository $er, TariffRepository $tr)
    {
        $this->logger = $logger;
        $this->kernel = $kernel;
        $this->em = $em;
        $this->ts = $ts;
        $this->sr = $sr;
        $this->er = $er;
        $this->tr = $tr;
    }

    /**
     * @param string $value
     *
     * @return float
     */
    protected function parseStringToFloat($value)
    {
        $stringParsedValue = str_replace('.', '', $value);
        $stringParsedValue = str_replace(',', '.', $stringParsedValue);

        return floatval($stringParsedValue);
    }
}
