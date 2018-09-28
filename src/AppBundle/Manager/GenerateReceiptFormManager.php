<?php

namespace AppBundle\Manager;

use AppBundle\Entity\Receipt;
use AppBundle\Entity\ReceiptLine;
use AppBundle\Entity\Student;
use AppBundle\Enum\ReceiptYearMonthEnum;
use AppBundle\Form\Model\GenerateReceiptItemModel;
use AppBundle\Form\Model\GenerateReceiptModel;
use AppBundle\Repository\EventRepository;
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
     * @var EventManager
     */
    private $eem;

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
     * @param EventRepository     $er
     * @param ReceiptRepository   $rr
     * @param EventManager        $eem
     */
    public function __construct(LoggerInterface $logger, KernelInterface $kernel, EntityManager $em, TranslatorInterface $ts, StudentRepository $sr, EventRepository $er, ReceiptRepository $rr, EventManager $eem)
    {
        parent::__construct($logger, $kernel, $em, $ts, $sr, $er);
        $this->rr = $rr;
        $this->eem = $eem;
    }

    /**
     * @param int  $year
     * @param int  $month
     * @param bool $enableEmailDelivery
     *
     * @return int
     *
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    private function commonFastGenerateReciptsForYearAndMonth($year, $month, $enableEmailDelivery = false)
    {
        $generatedReceiptsAmount = 0;

        // group lessons
        $studentsInGroupLessons = $this->sr->getGroupLessonStudentsInEventsForYearAndMonthSortedBySurnameWithValidTariff($year, $month);
        /** @var Student $student */
        foreach ($studentsInGroupLessons as $student) {
            /** @var Receipt $previousReceipt */
            $previousReceipt = $this->rr->findOnePreviousGroupLessonsReceiptByStudentYearAndMonthOrNull($student, $year, $month);
            if (is_null($previousReceipt)) {
                // create and persist new receipt
                ++$generatedReceiptsAmount;
                $description = $this->ts->trans('backend.admin.invoiceLine.generator.group_lessons_line', array('%month%' => ReceiptYearMonthEnum::getTranslatedMonthEnumArray()[$month], '%year%' => $year), 'messages');
                $receiptLine = new ReceiptLine();
                $receiptLine
                    ->setStudent($student)
                    ->setDescription($description)
                    ->setUnits(1)
                    ->setPriceUnit($student->getTariff()->getPrice())
                    ->setDiscount($student->calculateMonthlyDiscount())
                    ->setTotal($receiptLine->getPriceUnit() - $receiptLine->getDiscount())
                ;
                $receipt = new Receipt();
                $receipt
                    ->setDate(new \DateTime())
                    ->setStudent($student)
                    ->setPerson($student->getParent() ? $student->getParent() : null)
                    ->setIsPayed(false)
                    ->setIsSepaXmlGenerated(false)
                    ->setIsForPrivateLessons(false)
                    ->setIsSended(false)
                    ->setYear($year)
                    ->setMonth($month)
                    ->addLine($receiptLine)
                ;
                if ($enableEmailDelivery) {
                    $receipt
                        ->setIsSended(true)
                        ->setSendDate(new \DateTime())
                    ;
                }
                $this->em->persist($receipt);
            }
        }

        // private lessons (in previous month period)
        $oldYear = $year;
        $oldMonth = $month;
        $month = $month - 1;
        if (0 == $month) {
            $month = 12;
            $year = $year - 1;
        }
        $studentsInPrivateLessons = $this->sr->getPrivateLessonStudentsInEventsForYearAndMonthSortedBySurnameWithValidTariff($year, $month);
        /** @var Student $student */
        foreach ($studentsInPrivateLessons as $student) {
            /** @var Receipt $previousReceipt */
            $previousReceipt = $this->rr->findOnePreviousPrivateLessonsReceiptByStudentYearAndMonthOrNull($student, $oldYear, $oldMonth);
            if (is_null($previousReceipt)) {
                // create and persist new receipt
                ++$generatedReceiptsAmount;
                $description = $this->ts->trans('backend.admin.invoiceLine.generator.private_lessons_line', array('%month%' => ReceiptYearMonthEnum::getTranslatedMonthEnumArray()[$month], '%year%' => $year), 'messages');
                $receiptLine = new ReceiptLine();
                $receiptLine
                    ->setStudent($student)
                    ->setDescription($description)
                    ->setUnits($this->er->getPrivateLessonsAmountByStudentYearAndMonth($student, $year, $month))
                    ->setPriceUnit($this->eem->getCurrentPrivateLessonsTariffForEvents($studentsInPrivateLessons)->getPrice())
                    ->setDiscount(0)
                    ->setTotal($receiptLine->getPriceUnit() - $receiptLine->getDiscount())
                ;
                $receipt = new Receipt();
                $receipt
                    ->setDate(new \DateTime())
                    ->setStudent($student)
                    ->setPerson($student->getParent() ? $student->getParent() : null)
                    ->setIsPayed(false)
                    ->setIsSepaXmlGenerated(false)
                    ->setIsForPrivateLessons(true)
                    ->setIsSended(false)
                    ->setYear($oldYear)
                    ->setMonth($oldMonth)
                    ->addLine($receiptLine)
                ;
                if ($enableEmailDelivery) {
                    $receipt
                        ->setIsSended(true)
                        ->setSendDate(new \DateTime())
                    ;
                }
                $this->em->persist($receipt);
            }
        }
        $this->em->flush();

        if ($enableEmailDelivery) {
            $ids = array();
            $this->logger->info('[GRFM] commonFastGenerateReciptsForYearAndMonth call');
            $this->logger->info('[GRFM] '.$generatedReceiptsAmount.' records managed');
            $receipts = $this->rr->findBy(array(
                'year' => $oldYear,
                'month' => $oldMonth,
                'isSended' => false,
            ));
            if (count($receipts) > 0) {
                /** @var Receipt $receipt */
                foreach ($receipts as $receipt) {
                    $ids[] = $receipt->getId();
                    $receipt
                        ->setIsSended(true)
                        ->setSendDate(new \DateTime())
                    ;
                }
                $this->em->flush();
                $phpBinaryFinder = new PhpExecutableFinder();
                $phpBinaryPath = $phpBinaryFinder->find();
                $command = 'nohup '.$phpBinaryPath.' '.$this->kernel->getRootDir().DIRECTORY_SEPARATOR.'console app:deliver:receipts:batch '.implode(' ', $ids).' --force --env='.$this->kernel->getEnvironment().' 2>&1 > /dev/null &';
                $this->logger->info('[GRFM] '.$command);
                $process = new Process($command);
                $process->setTimeout(null);
                $process->run();
            } else {
                $this->logger->info('[GRFM] commonFastGenerateReciptsForYearAndMonth nothing send, all receipts are preivously sended.');
            }
        }
        $this->logger->info('[GRFM] commonFastGenerateReciptsForYearAndMonth EOF');

        return $generatedReceiptsAmount;
    }

    /**
     * @param int $year
     * @param int $month
     *
     * @return int
     *
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function fastGenerateReciptsForYearAndMonth($year, $month)
    {
        return $this->commonFastGenerateReciptsForYearAndMonth($year, $month, false);
    }

    /**
     * @param int $year
     * @param int $month
     *
     * @return int
     *
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function fastGenerateReciptsForYearAndMonthAndDeliverEmail($year, $month)
    {
        return $this->commonFastGenerateReciptsForYearAndMonth($year, $month, true);
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

        // group lessons
        $studentsInGroupLessons = $this->sr->getGroupLessonStudentsInEventsForYearAndMonthSortedBySurnameWithValidTariff($year, $month);
        /** @var Student $student */
        foreach ($studentsInGroupLessons as $student) {
            /** @var Receipt $previousReceipt */
            $previousReceipt = $this->rr->findOnePreviousGroupLessonsReceiptByStudentYearAndMonthOrNull($student, $year, $month);
            if (!is_null($previousReceipt)) {
                // old
                if (count($previousReceipt->getLines()) > 0) {
                    /** @var ReceiptLine $previousItem */
                    $previousItem = $previousReceipt->getLines()[0];
                    $generateReceiptItem = new GenerateReceiptItemModel();
                    $generateReceiptItem
                        ->setStudentId($student->getId())
                        ->setStudentName($student->getFullCanonicalName())
                        ->setUnits($previousItem->getUnits())
                        ->setUnitPrice($previousItem->getPriceUnit())
                        ->setDiscount($previousItem->getDiscount())
                        ->setIsReadyToGenerate(false)
                        ->setIsPreviouslyGenerated(true)
                        ->setIsPrivateLessonType(false)
                    ;
                    $generateReceipt->addItem($generateReceiptItem);
                }
            } else {
                // new
                $generateReceiptItem = new GenerateReceiptItemModel();
                $generateReceiptItem
                    ->setStudentId($student->getId())
                    ->setStudentName($student->getFullCanonicalName())
                    ->setUnits(1)
                    ->setUnitPrice($student->getTariff()->getPrice())
                    ->setDiscount($student->calculateMonthlyDiscount())
                    ->setIsReadyToGenerate(true)
                    ->setIsPreviouslyGenerated(false)
                    ->setIsPrivateLessonType(false)
                ;
                $generateReceipt->addItem($generateReceiptItem);
            }
        }

        // private lessons (in previous month period)
        $oldYear = $year;
        $oldMonth = $month;
        $month = $month - 1;
        if (0 == $month) {
            $month = 12;
            $year = $year - 1;
        }
        $studentsInPrivateLessons = $this->sr->getPrivateLessonStudentsInEventsForYearAndMonthSortedBySurnameWithValidTariff($year, $month);
        /** @var Student $student */
        foreach ($studentsInPrivateLessons as $student) {
            /** @var Receipt $previousReceipt */
            $previousReceipt = $this->rr->findOnePreviousPrivateLessonsReceiptByStudentYearAndMonthOrNull($student, $oldYear, $oldMonth);
            if (!is_null($previousReceipt)) {
                // old
                if (count($previousReceipt->getLines()) > 0) {
                    /** @var ReceiptLine $previousItem */
                    $previousItem = $previousReceipt->getLines()[0];
                    $generateReceiptItem = new GenerateReceiptItemModel();
                    $generateReceiptItem
                        ->setStudentId($student->getId())
                        ->setStudentName($student->getFullCanonicalName())
                        ->setUnits($previousItem->getUnits())
                        ->setUnitPrice($previousItem->getPriceUnit())
                        ->setDiscount($previousItem->getDiscount())
                        ->setIsReadyToGenerate(false)
                        ->setIsPreviouslyGenerated(true)
                        ->setIsPrivateLessonType(true)
                    ;
                    $generateReceipt->addItem($generateReceiptItem);
                }
            } else {
                // new
                $privateLessons = $this->er->getPrivateLessonsByStudentYearAndMonth($student, $year, $month);
                $generateReceiptItem = new GenerateReceiptItemModel();
                $generateReceiptItem
                    ->setStudentId($student->getId())
                    ->setStudentName($student->getFullCanonicalName())
                    ->setUnits(floatval(count($privateLessons)))
                    ->setUnitPrice($this->eem->getCurrentPrivateLessonsTariffForEvents($privateLessons)->getPrice())
                    ->setDiscount(0)
                    ->setIsReadyToGenerate(true)
                    ->setIsPreviouslyGenerated(false)
                    ->setIsPrivateLessonType(true)
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
                if (array_key_exists('units', $item) && array_key_exists('unitPrice', $item) && array_key_exists('discount', $item) && array_key_exists('studentId', $item)) {
                    $studentId = intval($item['studentId']);
                    /** @var Student $student */
                    $student = $this->sr->find($studentId);
                    if ($student) {
                        $generateReceiptItem = new GenerateReceiptItemModel();
                        $generateReceiptItem
                            ->setStudentId($student->getId())
                            ->setStudentName($student->getFullCanonicalName())
                            ->setUnits($this->parseStringToFloat($item['units']))
                            ->setUnitPrice($this->parseStringToFloat($item['unitPrice']))
                            ->setDiscount($this->parseStringToFloat($item['discount']))
                            ->setIsReadyToGenerate(array_key_exists('isReadyToGenerate', $item))
                            ->setIsPreviouslyGenerated(array_key_exists('isPreviouslyGenerated', $item))
                            ->setIsPrivateLessonType(array_key_exists('isPrivateLessonType', $item))
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
     * @param bool                 $markReceiptAsSended
     *
     * @return int
     *
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function persistFullModelForm(GenerateReceiptModel $generateReceiptModel, $markReceiptAsSended = false)
    {
        $recordsParsed = 0;
        /** @var GenerateReceiptItemModel $generateReceiptItemModel */
        foreach ($generateReceiptModel->getItems() as $generateReceiptItemModel) {
            if ($generateReceiptItemModel->isReadyToGenerate()) {
                if (!$generateReceiptItemModel->isPrivateLessonType()) {
                    // group lessons
                    /** @var Receipt $previousReceipt */
                    $previousReceipt = $this->rr->findOnePreviousGroupLessonsReceiptByStudentIdYearAndMonthOrNull($generateReceiptItemModel->getStudentId(), $generateReceiptModel->getYear(), $generateReceiptModel->getMonth());
                    $description = $this->ts->trans('backend.admin.invoiceLine.generator.group_lessons_line', array('%month%' => ReceiptYearMonthEnum::getTranslatedMonthEnumArray()[$generateReceiptModel->getMonth()], '%year%' => $generateReceiptModel->getYear()), 'messages');
                    $isForPrivateLessons = false;
                } else {
                    // private lessons
                    $month = $generateReceiptModel->getMonth() - 1;
                    $year = $generateReceiptModel->getYear();
                    if (0 == $month) {
                        $month = 12;
                        $year = $year - 1;
                    }
                    /** @var Receipt $previousReceipt */
                    $previousReceipt = $this->rr->findOnePreviousPrivateLessonsReceiptByStudentIdYearAndMonthOrNull($generateReceiptItemModel->getStudentId(), $generateReceiptModel->getYear(), $generateReceiptModel->getMonth());
                    $description = $this->ts->trans('backend.admin.invoiceLine.generator.private_lessons_line', array('%month%' => ReceiptYearMonthEnum::getTranslatedMonthEnumArray()[$month], '%year%' => $year), 'messages');
                    $isForPrivateLessons = true;
                }
                ++$recordsParsed;
                /** @var Student|null $student */
                $student = $this->sr->find($generateReceiptItemModel->getStudentId());
                if (!is_null($previousReceipt)) {
                    // update existing receipt
                    if (1 === count($previousReceipt->getLines())) {
                        $previousReceipt->setDate(new \DateTime());
                        /** @var ReceiptLine $receiptLine */
                        $receiptLine = $previousReceipt->getLines()[0];
                        $receiptLine
                            ->setStudent($student)
                            ->setDescription($description)
                            ->setUnits($generateReceiptItemModel->getUnits())
                            ->setPriceUnit($generateReceiptItemModel->getUnitPrice())
                            ->setDiscount($generateReceiptItemModel->getDiscount())
                            ->setTotal($generateReceiptItemModel->getUnits() * $generateReceiptItemModel->getUnitPrice() - $generateReceiptItemModel->getDiscount())
                        ;
                        $previousReceipt
                            ->setBaseAmount($receiptLine->getTotal())
                            ->setIsForPrivateLessons($isForPrivateLessons)
                        ;
                        if ($markReceiptAsSended) {
                            $previousReceipt
                                ->setIsSended(true)
                                ->setSendDate(new \DateTime())
                            ;
                        }
                        $this->em->flush();
                    }
                } else {
                    // create new receipt
                    $receiptLine = new ReceiptLine();
                    $receiptLine
                        ->setStudent($student)
                        ->setDescription($description)
                        ->setUnits($generateReceiptItemModel->getUnits())
                        ->setPriceUnit($generateReceiptItemModel->getUnitPrice())
                        ->setDiscount($generateReceiptItemModel->getDiscount())
                        ->setTotal($generateReceiptItemModel->getUnits() * $generateReceiptItemModel->getUnitPrice() - $generateReceiptItemModel->getDiscount())
                    ;
                    $receipt = new Receipt();
                    $receipt
                        ->setDate(new \DateTime())
                        ->setStudent($student)
                        ->setPerson($student->getParent() ? $student->getParent() : null)
                        ->setIsPayed(false)
                        ->setIsSepaXmlGenerated(false)
                        ->setIsSended(false)
                        ->setYear($generateReceiptModel->getYear())
                        ->setMonth($generateReceiptModel->getMonth())
                        ->addLine($receiptLine)
                        ->setIsForPrivateLessons($isForPrivateLessons)
                    ;
                    if ($markReceiptAsSended) {
                        $receipt
                            ->setIsSended(true)
                            ->setSendDate(new \DateTime())
                        ;
                    }
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
        $recordsParsed = $this->persistFullModelForm($generateReceiptModel, true);
        $this->logger->info('[GRFM] '.$recordsParsed.' records managed');

        if (0 < $recordsParsed) {
            $ids = array();
            $phpBinaryFinder = new PhpExecutableFinder();
            $phpBinaryPath = $phpBinaryFinder->find();
            /** @var GenerateReceiptItemModel $generateReceiptItemModel */
            foreach ($generateReceiptModel->getItems() as $generateReceiptItemModel) {
                if (!$generateReceiptItemModel->isPrivateLessonType()) {
                    // group lessons
                    /** @var Receipt $previousReceipt */
                    $previousReceipt = $this->rr->findOnePreviousGroupLessonsReceiptByStudentIdYearAndMonthOrNull($generateReceiptItemModel->getStudentId(), $generateReceiptModel->getYear(), $generateReceiptModel->getMonth());
                } else {
                    // private lessons
                    /** @var Receipt $previousReceipt */
                    $previousReceipt = $this->rr->findOnePreviousPrivateLessonsReceiptByStudentIdYearAndMonthOrNull($generateReceiptItemModel->getStudentId(), $generateReceiptModel->getYear(), $generateReceiptModel->getMonth());
                }
                if ($previousReceipt && 1 === count($previousReceipt->getLines()) && $generateReceiptItemModel->isReadyToGenerate()) {
                    $ids[] = $previousReceipt->getId();
                }
            }
            if (count($ids) > 0) {
                $command = 'nohup '.$phpBinaryPath.' '.$this->kernel->getRootDir().DIRECTORY_SEPARATOR.'console app:deliver:receipts:batch '.implode(' ', $ids).' --force --env='.$this->kernel->getEnvironment().' 2>&1 > /dev/null &';
                $this->logger->info('[GRFM] '.$command);
                $process = new Process($command);
                $process->setTimeout(null);
                $process->run();
            }
        }
        $this->logger->info('[GRFM] persistAndDeliverFullModelForm EOF');

        return $recordsParsed;
    }
}
