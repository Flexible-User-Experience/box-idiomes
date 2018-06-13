<?php

namespace AppBundle\Manager;

use AppBundle\Entity\Invoice;
use AppBundle\Entity\InvoiceLine;
use AppBundle\Entity\Student;
use AppBundle\Enum\InvoiceYearMonthEnum;
use AppBundle\Enum\TariffTypeEnum;
use AppBundle\Form\Model\GenerateInvoiceItemModel;
use AppBundle\Form\Model\GenerateInvoiceModel;
use AppBundle\Repository\ReceiptRepository;
use AppBundle\Repository\StudentRepository;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * Class GenerateReceiptFormManager.
 *
 * @category Manager
 */
class GenerateReceiptFormManager
{
    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @var TranslatorInterface
     */
    private $ts;

    /**
     * @var StudentRepository
     */
    private $sr;

    /**
     * @var ReceiptRepository
     */
    private $rr;

    /**
     * Methods.
     */

    /**
     * GenerateInvoiceFormManager constructor.
     *
     * @param EntityManager       $em
     * @param TranslatorInterface $ts
     * @param StudentRepository   $sr
     * @param ReceiptRepository   $rr
     */
    public function __construct(EntityManager $em, TranslatorInterface $ts, StudentRepository $sr, ReceiptRepository $rr)
    {
        $this->em = $em;
        $this->ts = $ts;
        $this->sr = $sr;
        $this->rr = $rr;
    }

    /**
     * @param int $year
     * @param int $month
     *
     * @return GenerateInvoiceModel
     *
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function buildFullModelForm($year, $month)
    {
        $generateInvoice = new GenerateInvoiceModel();
        $generateInvoice
            ->setYear($year)
            ->setMonth($month)
        ;
        $students = $this->sr->getStudentsInEventsByYearAndMonthSortedBySurnameWithValidTariff($year, $month);
        /** @var Student $student */
        foreach ($students as $student) {
            /** @var Invoice $previousInvoice */
            $previousInvoice = $this->ir->findOnePreviousInvoiceByStudentYearAndMonthOrNull($student, $year, $month);
            if (!is_null($previousInvoice)) {
                // old
                if (count($previousInvoice->getLines()) > 0) {
                    /** @var InvoiceLine $previousItem */
                    $previousItem = $previousInvoice->getLines()[0];
                    $generateInvoiceItem = new GenerateInvoiceItemModel();
                    $generateInvoiceItem
                        ->setStudent($student)
                        ->setUnits($previousItem->getUnits())
                        ->setUnitPrice($previousItem->getPriceUnit())
                        ->setDiscount($previousItem->getDiscount())
                        ->setIsReadyToGenerate(false)
                        ->setIsPreviouslyGenerated(true)
                    ;
                    $generateInvoice->addItem($generateInvoiceItem);
                }
            } else {
                // new
                if (TariffTypeEnum::TARIFF_SIGLE_HOUR == $student->getTariff()->getType()) {
                    // TODO set units acording to assisted classes in selected year & month before
                }
                $generateInvoiceItem = new GenerateInvoiceItemModel();
                $generateInvoiceItem
                    ->setStudent($student)
                    ->setUnits(1)
                    ->setUnitPrice($student->getTariff()->getPrice())
                    ->setDiscount($student->calculateMonthlyDiscount())
                    ->setIsReadyToGenerate(true)
                    ->setIsPreviouslyGenerated(false)
                ;
                $generateInvoice->addItem($generateInvoiceItem);
            }
        }

        return $generateInvoice;
    }

    /**
     * @param array $requestArray
     *
     * @return GenerateInvoiceModel
     */
    public function transformRequestArrayToModel($requestArray)
    {
        $generateInvoice = new GenerateInvoiceModel();
        if (array_key_exists('year', $requestArray)) {
            $generateInvoice->setYear(intval($requestArray['year']));
        }
        if (array_key_exists('month', $requestArray)) {
            $generateInvoice->setMonth(intval($requestArray['month']));
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
                        $generateInvoiceItem = new GenerateInvoiceItemModel();
                        $generateInvoiceItem
                            ->setStudent($student)
                            ->setUnits($this->parseStringToFloat($item['units']))
                            ->setUnitPrice($this->parseStringToFloat($item['unitPrice']))
                            ->setDiscount($this->parseStringToFloat($item['discount']))
                            ->setIsReadyToGenerate(array_key_exists('isReadyToGenerate', $item))
                            ->setIsPreviouslyGenerated(array_key_exists('isPreviouslyGenerated', $item))
                        ;
                        $generateInvoice->addItem($generateInvoiceItem);
                    }
                }
            }
        }

        return $generateInvoice;
    }

    /**
     * @param GenerateInvoiceModel $generateInvoiceModel
     *
     * @return int
     *
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function persistFullModelForm(GenerateInvoiceModel $generateInvoiceModel)
    {
        $recordsParsed = 0;
        /** @var GenerateInvoiceItemModel $generateInvoiceItemModel */
        foreach ($generateInvoiceModel->getItems() as $generateInvoiceItemModel) {
            if ($generateInvoiceItemModel->isReadyToGenerate()) {
                ++$recordsParsed;
                if ($generateInvoiceItemModel->isPreviouslyGenerated()) {
                    /** @var Invoice $previousInvoice */
                    $previousInvoice = $this->ir->findOnePreviousInvoiceByStudentYearAndMonthOrNull($generateInvoiceItemModel->getStudent(), $generateInvoiceModel->getYear(), $generateInvoiceModel->getMonth());
                    if (1 === count($previousInvoice->getLines())) {
                        /** @var InvoiceLine $invoiceLine */
                        $invoiceLine = $previousInvoice->getLines()[0];
                        $invoiceLine
                            ->setStudent($generateInvoiceItemModel->getStudent())
                            ->setDescription($this->ts->trans('backend.admin.invoiceLine.generator.line', array('%month%' => InvoiceYearMonthEnum::getTranslatedMonthEnumArray()[$generateInvoiceModel->getMonth()], '%year%' => $generateInvoiceModel->getYear()), 'messages'))
                            ->setUnits($generateInvoiceItemModel->getUnits())
                            ->setPriceUnit($generateInvoiceItemModel->getUnitPrice())
                            ->setDiscount($generateInvoiceItemModel->getDiscount())
                            ->setTotal($generateInvoiceItemModel->getUnits() * $generateInvoiceItemModel->getUnitPrice() - $generateInvoiceItemModel->getDiscount())
                        ;
                        $previousInvoice
                            ->setTaxParcentage(0)
                            ->setBaseAmount($invoiceLine->getTotal())
                            ->setIrpf($previousInvoice->calculateIrpf())
                            ->setTotalAmount($invoiceLine->getTotal() - $previousInvoice->getIrpf())
                        ;
                        $this->em->flush();
                    }
                } else {
                    // create new invoice
                    $invoiceLine = new InvoiceLine();
                    $invoiceLine
                        ->setStudent($generateInvoiceItemModel->getStudent())
                        ->setDescription($this->ts->trans('backend.admin.invoiceLine.generator.line', array('%month%' => InvoiceYearMonthEnum::getTranslatedMonthEnumArray()[$generateInvoiceModel->getMonth()], '%year%' => $generateInvoiceModel->getYear()), 'messages'))
                        ->setUnits($generateInvoiceItemModel->getUnits())
                        ->setPriceUnit($generateInvoiceItemModel->getUnitPrice())
                        ->setDiscount($generateInvoiceItemModel->getDiscount())
                        ->setTotal($generateInvoiceItemModel->getUnits() * $generateInvoiceItemModel->getUnitPrice() - $generateInvoiceItemModel->getDiscount())
                    ;
                    $invoice = new Invoice();
                    $invoice
                        ->setStudent($generateInvoiceItemModel->getStudent())
                        ->setPerson($generateInvoiceItemModel->getStudent()->getParent() ? $generateInvoiceItemModel->getStudent()->getParent() : null)
                        ->setDate(new \DateTime())
                        ->setIsPayed(false)
                        ->setYear($generateInvoiceModel->getYear())
                        ->setMonth($generateInvoiceModel->getMonth())
                        ->addLine($invoiceLine)
                        ->setIrpf($invoice->calculateIrpf())
                        ->setTaxParcentage(0)
                        ->setTotalAmount($invoiceLine->getTotal() - $invoice->getIrpf())
                    ;
                    $this->em->persist($invoice);
                }
            }
        }
        $this->em->flush();

        return $recordsParsed;
    }

    /**
     * @param string $value
     *
     * @return float
     */
    private function parseStringToFloat($value)
    {
        $stringParsedValue = str_replace('.', '', $value);
        $stringParsedValue = str_replace(',', '.', $stringParsedValue);

        return floatval($stringParsedValue);
    }
}
