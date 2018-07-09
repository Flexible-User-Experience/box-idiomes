<?php

namespace AppBundle\Manager;

use AppBundle\Repository\StudentRepository;
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
     */
    public function __construct(LoggerInterface $logger, KernelInterface $kernel, EntityManager $em, TranslatorInterface $ts, StudentRepository $sr)
    {
        $this->logger = $logger;
        $this->kernel = $kernel;
        $this->em = $em;
        $this->ts = $ts;
        $this->sr = $sr;
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
