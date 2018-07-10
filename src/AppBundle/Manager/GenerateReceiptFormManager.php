<?php

namespace AppBundle\Manager;

use AppBundle\Entity\Receipt;
use AppBundle\Entity\ReceiptLine;
use AppBundle\Entity\Student;
use AppBundle\Enum\ReceiptYearMonthEnum;
use AppBundle\Enum\TariffTypeEnum;
use AppBundle\Form\Model\GenerateReceiptItemModel;
use AppBundle\Form\Model\GenerateReceiptModel;
use AppBundle\Repository\ReceiptRepository;
use AppBundle\Repository\StudentRepository;
use Doctrine\ORM\EntityManager;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Process\PhpExecutableFinder;
use Symfony\Component\Translation\TranslatorInterface;
use Symfony\Component\Process\Process;

/**
 * Class GenerateReceiptFormManager.
 *
 * @category Manager
 */
class GenerateReceiptFormManager extends AbstractGenerateReceiptInvoiceFormManager
{
    /**
     * @var ReceiptRepository
     */
    private $rr;

    /**
     * Methods.
     */

    /**
     * GenerateReceiptFormManager constructor.
     *
     * @param LoggerInterface     $logger
     * @param KernelInterface     $kernel
     * @param EntityManager       $em
     * @param TranslatorInterface $ts
     * @param StudentRepository   $sr
     * @param ReceiptRepository   $rr
     */
    public function __construct(LoggerInterface $logger, KernelInterface $kernel, EntityManager $em, TranslatorInterface $ts, StudentRepository $sr, ReceiptRepository $rr)
    {
        parent::__construct($logger, $kernel, $em, $ts, $sr);
        $this->rr = $rr;
    }

    /**
     * @param int $year
     * @param int $month
     *
     * @return GenerateReceiptModel
     *
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function buildFullModelForm($year, $month)
    {
        $generateReceipt = new GenerateReceiptModel();
        $generateReceipt
            ->setYear($year)
            ->setMonth($month)
        ;
        $students = $this->sr->getStudentsInEventsForYearAndMonthSortedBySurnameWithValidTariff($year, $month);
        /** @var Student $student */
        foreach ($students as $student) {
            /** @var Receipt $previousReceipt */
            $previousReceipt = $this->rr->findOnePreviousReceiptByStudentYearAndMonthOrNull($student, $year, $month);
            if (!is_null($previousReceipt)) {
                // old
                if (count($previousReceipt->getLines()) > 0) {
                    /** @var ReceiptLine $previousItem */
                    $previousItem = $previousReceipt->getLines()[0];
                    $generateReceiptItem = new GenerateReceiptItemModel();
                    $generateReceiptItem
                        ->setStudent($student)
                        ->setUnits($previousItem->getUnits())
                        ->setUnitPrice($previousItem->getPriceUnit())
                        ->setDiscount($previousItem->getDiscount())
                        ->setIsReadyToGenerate(false)
                        ->setIsPreviouslyGenerated(true)
                    ;
                    $generateReceipt->addItem($generateReceiptItem);
                }
            } else {
                // new
                if (TariffTypeEnum::TARIFF_SIGLE_HOUR == $student->getTariff()->getType()) {
                    // TODO set units acording to assisted classes in selected year & month before
                }
                $generateReceiptItem = new GenerateReceiptItemModel();
                $generateReceiptItem
                    ->setStudent($student)
                    ->setUnits(1)
                    ->setUnitPrice($student->getTariff()->getPrice())
                    ->setDiscount($student->calculateMonthlyDiscount())
                    ->setIsReadyToGenerate(true)
                    ->setIsPreviouslyGenerated(false)
                ;
                $generateReceipt->addItem($generateReceiptItem);
            }
        }

        return $generateReceipt;
    }

    /**
     * @param array $requestArray
     *
     * @return GenerateReceiptModel
     */
    public function transformRequestArrayToModel($requestArray)
    {
        $generateReceipt = new GenerateReceiptModel();
        if (array_key_exists('year', $requestArray)) {
            $generateReceipt->setYear(intval($requestArray['year']));
        }
        if (array_key_exists('month', $requestArray)) {
            $generateReceipt->setMonth(intval($requestArray['month']));
        }
        if (array_key_exists('items', $requestArray)) {
            $items = $requestArray['items'];
            /** @var array $item */
            foreach ($items as $item) {
                if (array_key_exists('units', $item) && array_key_exists('unitPrice', $item) && array_key_exists('discount', $item) && array_key_exists('student', $item)) {
                    $studentId = intval($item['student']);
                    /** @var Student $student */
                    $student = $this->sr->find($studentId);
                    if ($student) {
                        $generateReceiptItem = new GenerateReceiptItemModel();
                        $generateReceiptItem
                            ->setStudent($student)
                            ->setUnits($this->parseStringToFloat($item['units']))
                            ->setUnitPrice($this->parseStringToFloat($item['unitPrice']))
                            ->setDiscount($this->parseStringToFloat($item['discount']))
                            ->setIsReadyToGenerate(array_key_exists('isReadyToGenerate', $item))
                            ->setIsPreviouslyGenerated(array_key_exists('isPreviouslyGenerated', $item))
                        ;
                        $generateReceipt->addItem($generateReceiptItem);
                    }
                }
            }
        }

        return $generateReceipt;
    }

    /**
     * @param GenerateReceiptModel $generateReceiptModel
     *
     * @return int
     *
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function persistFullModelForm(GenerateReceiptModel $generateReceiptModel)
    {
        $recordsParsed = 0;
        /** @var GenerateReceiptItemModel $generateReceiptItemModel */
        foreach ($generateReceiptModel->getItems() as $generateReceiptItemModel) {
            if ($generateReceiptItemModel->isReadyToGenerate()) {
                ++$recordsParsed;
                /** @var Receipt $previousReceipt */
                $previousReceipt = $this->rr->findOnePreviousReceiptByStudentYearAndMonthOrNull($generateReceiptItemModel->getStudent(), $generateReceiptModel->getYear(), $generateReceiptModel->getMonth());
                if (!is_null($previousReceipt)) {
                    // update existing receipt
                    if (1 === count($previousReceipt->getLines())) {
                        $previousReceipt->setDate(new \DateTime());
                        /** @var ReceiptLine $receiptLine */
                        $receiptLine = $previousReceipt->getLines()[0];
                        $receiptLine
                            ->setStudent($generateReceiptItemModel->getStudent())
                            ->setDescription($this->ts->trans('backend.admin.invoiceLine.generator.line', array('%month%' => ReceiptYearMonthEnum::getTranslatedMonthEnumArray()[$generateReceiptModel->getMonth()], '%year%' => $generateReceiptModel->getYear()), 'messages'))
                            ->setUnits($generateReceiptItemModel->getUnits())
                            ->setPriceUnit($generateReceiptItemModel->getUnitPrice())
                            ->setDiscount($generateReceiptItemModel->getDiscount())
                            ->setTotal($generateReceiptItemModel->getUnits() * $generateReceiptItemModel->getUnitPrice() - $generateReceiptItemModel->getDiscount())
                        ;
                        $previousReceipt->setBaseAmount($receiptLine->getTotal());
                        $this->em->flush();
                    }
                } else {
                    // create new receipt
                    $receiptLine = new ReceiptLine();
                    $receiptLine
                        ->setStudent($generateReceiptItemModel->getStudent())
                        ->setDescription($this->ts->trans('backend.admin.invoiceLine.generator.line', array('%month%' => ReceiptYearMonthEnum::getTranslatedMonthEnumArray()[$generateReceiptModel->getMonth()], '%year%' => $generateReceiptModel->getYear()), 'messages'))
                        ->setUnits($generateReceiptItemModel->getUnits())
                        ->setPriceUnit($generateReceiptItemModel->getUnitPrice())
                        ->setDiscount($generateReceiptItemModel->getDiscount())
                        ->setTotal($generateReceiptItemModel->getUnits() * $generateReceiptItemModel->getUnitPrice() - $generateReceiptItemModel->getDiscount())
                    ;
                    $receipt = new Receipt();
                    $receipt
                        ->setDate(new \DateTime())
                        ->setStudent($generateReceiptItemModel->getStudent())
                        ->setPerson($generateReceiptItemModel->getStudent()->getParent() ? $generateReceiptItemModel->getStudent()->getParent() : null)
                        ->setDate(new \DateTime())
                        ->setIsPayed(false)
                        ->setYear($generateReceiptModel->getYear())
                        ->setMonth($generateReceiptModel->getMonth())
                        ->addLine($receiptLine)
                    ;
                    $this->em->persist($receipt);
                }
            }
        }
        $this->em->flush();

        return $recordsParsed;
    }

    /**
     * @param GenerateReceiptModel $generateReceiptModel
     *
     * @return int
     *
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function persistAndDeliverFullModelForm(GenerateReceiptModel $generateReceiptModel)
    {
        $this->logger->info('[GRFM] persistAndDeliverFullModelForm call');
        $recordsParsed = $this->persistFullModelForm($generateReceiptModel);
        $this->logger->info('[GRFM] '.$recordsParsed.' records managed');

        if (0 < $recordsParsed) {
            $phpBinaryFinder = new PhpExecutableFinder();
            $phpBinaryPath = $phpBinaryFinder->find();
            /** @var GenerateReceiptItemModel $generateReceiptItemModel */
            foreach ($generateReceiptModel->getItems() as $generateReceiptItemModel) {
                /** @var Receipt $previousReceipt */
                $previousReceipt = $this->rr->findOnePreviousReceiptByStudentYearAndMonthOrNull($generateReceiptItemModel->getStudent(), $generateReceiptModel->getYear(), $generateReceiptModel->getMonth());
                if ($previousReceipt && 1 === count($previousReceipt->getLines()) && $generateReceiptItemModel->isReadyToGenerate()) {
                    $command = $phpBinaryPath.' '.$this->kernel->getRootDir().DIRECTORY_SEPARATOR.'console app:deliver:receipt '.$previousReceipt->getId().' --force --env='.$this->kernel->getEnvironment().' 2>&1 > /dev/null &';
                    $this->logger->info('[GRFM] '.$command);
                    $process = new Process($command);
                    $process->run();
                }
            }
        }
        $this->logger->info('[GRFM] persistAndDeliverFullModelForm EOF');

        return $recordsParsed;
    }
}
