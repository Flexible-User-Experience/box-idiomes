<?php

namespace AppBundle\Manager;

use AppBundle\Entity\Invoice;
use AppBundle\Entity\InvoiceLine;
use AppBundle\Entity\Student;
use AppBundle\Enum\InvoiceYearMonthEnum;
use AppBundle\Enum\TariffTypeEnum;
use AppBundle\Form\Model\GenerateInvoiceItemModel;
use AppBundle\Form\Model\GenerateInvoiceModel;
use AppBundle\Repository\InvoiceRepository;
use AppBundle\Repository\StudentRepository;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * Class GenerateInvoiceFormManager.
 *
 * @category Manager
 */
class GenerateInvoiceFormManager
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
     * @var InvoiceRepository
     */
    private $ir;

    /**
     * Methods.
     */

    /**
     * GenerateInvoiceFormManager constructor.
     *
     * @param EntityManager       $em
     * @param TranslatorInterface $ts
     * @param StudentRepository   $sr
     * @param InvoiceRepository   $ir
     */
    public function __construct(EntityManager $em, TranslatorInterface $ts, StudentRepository $sr, InvoiceRepository $ir)
    {
        $this->em = $em;
        $this->ts = $ts;
        $this->sr = $sr;
        $this->ir = $ir;
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
            $isPreviouslyGenerated = false;
            $previousInvoice = $this->ir->findOnePreviousInvoiceByStudentYearAndMonthOrNull($student, $year, $month);
            if (!is_null($previousInvoice)) {
                $isPreviouslyGenerated = true;
            }
            $units = 1;
            if (TariffTypeEnum::TARIFF_SIGLE_HOUR == $student->getTariff()->getType()) {
                // TODO set units acording to assisted classes in selected year & month
            }
            $generateInvoiceItem = new GenerateInvoiceItemModel();
            $generateInvoiceItem
                ->setStudent($student)
                ->setUnits($units)
                ->setUnitPrice($student->getTariff()->getPrice())
                ->setDiscount($student->calculateMonthlyDiscount())
                ->setIsReadyToGenerate($isPreviouslyGenerated ? false : true)
                ->setIsPreviouslyGenerated($isPreviouslyGenerated)
            ;
            $generateInvoice->addItem($generateInvoiceItem);
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
                    // TODO update old invoice
                    $previousInvoice = $this->ir->findOnePreviousInvoiceByStudentYearAndMonthOrNull($generateInvoiceItemModel->getStudent(), $generateInvoiceModel->getYear(), $generateInvoiceModel->getMonth());
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
