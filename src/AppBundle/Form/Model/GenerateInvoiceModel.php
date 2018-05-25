<?php

namespace AppBundle\Form\Model;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * Class GenerateInvoiceModel.
 *
 * @category FormModel
 */
class GenerateInvoiceModel
{
    /**
     * @var int
     */
    private $year;

    /**
     * @var int
     */
    private $month;

    /**
     * @var GenerateInvoiceItemModel[]
     */
    private $items;

    /**
     * Methods.
     */

    /**
     * GenerateInvoiceModel constructor.
     */
    public function __construct()
    {
        $this->items = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getYear()
    {
        return $this->year;
    }

    /**
     * @param int $year
     *
     * @return $this
     */
    public function setYear($year)
    {
        $this->year = $year;

        return $this;
    }

    /**
     * @return int
     */
    public function getMonth()
    {
        return $this->month;
    }

    /**
     * @param int $month
     *
     * @return $this
     */
    public function setMonth($month)
    {
        $this->month = $month;

        return $this;
    }

    /**
     * @return GenerateInvoiceItemModel[]
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * @param GenerateInvoiceItemModel[] $items
     *
     * @return $this
     */
    public function setItems($items)
    {
        $this->items = $items;

        return $this;
    }

    /**
     * @param GenerateInvoiceItemModel $item
     *
     * @return $this
     */
    public function addItem(GenerateInvoiceItemModel $item)
    {
        $this->items->add($item);

        return $this;
    }

    /**
     * @param GenerateInvoiceItemModel $item
     *
     * @return $this
     */
    public function removeItem(GenerateInvoiceItemModel $item)
    {
        $this->items->removeElement($item);

        return $this;
    }

    /**
     * @return float
     */
    public function getTotalAmount()
    {
        $result = 0.0;
        /** @var GenerateInvoiceItemModel $item */
        foreach ($this->getItems() as $item) {
            $result = $result + $item->getTotal();
        }

        return $result;
    }
}
