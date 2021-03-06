<?php

namespace AppBundle\Form\Model;

/**
 * Class GenerateReceiptItemModel.
 *
 * @category FormModel
 */
class GenerateReceiptItemModel
{
    /**
     * @var int
     */
    protected $studentId;

    /**
     * @var string
     */
    protected $studentName;

    /**
     * @var float
     */
    protected $units;

    /**
     * @var float
     */
    protected $unitPrice;

    /**
     * @var float
     */
    protected $discount;

    /**
     * @var bool
     */
    protected $isReadyToGenerate;

    /**
     * @var bool
     */
    protected $isPreviouslyGenerated;

    /**
     * @var bool
     */
    protected $isPrivateLessonType;

    /**
     * Methods.
     */

    /**
     * @return int
     */
    public function getStudentId()
    {
        return $this->studentId;
    }

    /**
     * @param int $studentId
     *
     * @return $this
     */
    public function setStudentId($studentId)
    {
        $this->studentId = $studentId;

        return $this;
    }

    /**
     * @return string
     */
    public function getStudentName()
    {
        return $this->studentName;
    }

    /**
     * @param string $studentName
     *
     * @return $this
     */
    public function setStudentName($studentName)
    {
        $this->studentName = $studentName;

        return $this;
    }

    /**
     * @return float
     */
    public function getUnits()
    {
        return $this->units;
    }

    /**
     * @param float $units
     *
     * @return $this
     */
    public function setUnits($units)
    {
        $this->units = $units;

        return $this;
    }

    /**
     * @return float
     */
    public function getUnitPrice()
    {
        return $this->unitPrice;
    }

    /**
     * @param float $unitPrice
     *
     * @return $this
     */
    public function setUnitPrice($unitPrice)
    {
        $this->unitPrice = $unitPrice;

        return $this;
    }

    /**
     * @return float
     */
    public function getDiscount()
    {
        return $this->discount;
    }

    /**
     * @param float $discount
     *
     * @return $this
     */
    public function setDiscount($discount)
    {
        $this->discount = $discount;

        return $this;
    }

    /**
     * @return bool
     */
    public function isReadyToGenerate()
    {
        return $this->isReadyToGenerate;
    }

    /**
     * @return bool
     */
    public function getIsReadyToGenerate()
    {
        return $this->isReadyToGenerate();
    }

    /**
     * @param bool $isReadyToGenerate
     *
     * @return $this
     */
    public function setIsReadyToGenerate($isReadyToGenerate)
    {
        $this->isReadyToGenerate = $isReadyToGenerate;

        return $this;
    }

    /**
     * @return bool
     */
    public function isPreviouslyGenerated()
    {
        return $this->isPreviouslyGenerated;
    }

    /**
     * @return bool
     */
    public function getIsPreviouslyGenerated()
    {
        return $this->isPreviouslyGenerated();
    }

    /**
     * @param bool $isPreviouslyGenerated
     *
     * @return $this
     */
    public function setIsPreviouslyGenerated($isPreviouslyGenerated)
    {
        $this->isPreviouslyGenerated = $isPreviouslyGenerated;

        return $this;
    }

    /**
     * @return bool
     */
    public function isPrivateLessonType()
    {
        return $this->isPrivateLessonType;
    }

    /**
     * @return bool
     */
    public function getPrivateLessonType()
    {
        return $this->isPrivateLessonType();
    }

    /**
     * @param bool $isPrivateLessonType
     *
     * @return $this
     */
    public function setIsPrivateLessonType($isPrivateLessonType)
    {
        $this->isPrivateLessonType = $isPrivateLessonType;

        return $this;
    }

    /**
     * @return float
     */
    public function getTotal()
    {
        return $this->getUnits() * $this->getUnitPrice() - $this->getDiscount();
    }
}
