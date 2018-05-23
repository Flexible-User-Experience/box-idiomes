<?php

namespace AppBundle\Manager;

use AppBundle\Entity\Student;
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
            $generateInvoiceItem
                ->setStudent($student)
                ->setUnits(1)
                ->setUnitPrice(24)
                ->setIsReadyToGenerate(true)
            ;
            $generateInvoice->addItem($generateInvoiceItem);
        }

        return $generateInvoice;
    }
}
