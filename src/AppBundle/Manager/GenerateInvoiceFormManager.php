<?php

namespace AppBundle\Manager;

use AppBundle\Entity\Student;
use AppBundle\Enum\TariffTypeEnum;
use AppBundle\Form\Model\GenerateInvoiceItemModel;
use AppBundle\Form\Model\GenerateInvoiceModel;
use AppBundle\Repository\StudentRepository;

/**
 * Class GenerateInvoiceFormManager.
 *
 * @category Manager
 */
class GenerateInvoiceFormManager
{
    /**
     * @var StudentRepository
     */
    private $sr;

    /**
     * Methods.
     */

    /**
     * GenerateInvoiceFormManager constructor.
     *
     * @param StudentRepository $sr
     */
    public function __construct(StudentRepository $sr)
    {
        $this->sr = $sr;
    }

    /**
     * @param int $year
     * @param int $month
     *
     * @return GenerateInvoiceModel
     */
    public function buildFormCompleted($year, $month)
    {
        $generateInvoice = new GenerateInvoiceModel();
        $students = $this->sr->getStudentsInEventsByYearAndMonthSortedBySurname($year, $month);
        /** @var Student $student */
        foreach ($students as $student) {
            $generateInvoiceItem = new GenerateInvoiceItemModel();
            $units = 1;
            if (TariffTypeEnum::TARIFF_SIGLE_HOUR == $student->getTariff()->getType()) {
                // TODO set units acording to assisted classes in selected year & month
            }
            $generateInvoiceItem
                ->setStudent($student)
                ->setUnits($units)
                ->setUnitPrice($student->getTariff()->getPrice())
                ->setDiscount($student->calculateMonthlyDiscount())
                ->setIsReadyToGenerate(true)
            ;
            $generateInvoice->addItem($generateInvoiceItem);
        }

        return $generateInvoice;
    }
}
