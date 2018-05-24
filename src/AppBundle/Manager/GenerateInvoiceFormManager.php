<?php

namespace AppBundle\Manager;

use AppBundle\Entity\Student;
use AppBundle\Enum\TariffTypeEnum;
use AppBundle\Form\Model\GenerateInvoiceItemModel;
use AppBundle\Form\Model\GenerateInvoiceModel;
use AppBundle\Repository\InvoiceRepository;
use AppBundle\Repository\StudentRepository;
use Doctrine\ORM\EntityManager;

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
     * @param EntityManager     $em
     * @param StudentRepository $sr
     * @param InvoiceRepository $ir
     */
    public function __construct(EntityManager $em, StudentRepository $sr, InvoiceRepository $ir)
    {
        $this->em = $em;
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
     * @param GenerateInvoiceModel $generateInvoiceModel
     *
     * @return int
     */
    public function persistFullModelForm(GenerateInvoiceModel $generateInvoiceModel)
    {
        $invoicesAmount = 0;
        /** @var GenerateInvoiceItemModel $generateInvoiceItemModel */
        foreach ($generateInvoiceModel->getItems() as $generateInvoiceItemModel) {
            if ($generateInvoiceItemModel->isReadyToGenerate()) {
                ++$invoicesAmount;
            }
        }

        return $invoicesAmount;
    }
}
