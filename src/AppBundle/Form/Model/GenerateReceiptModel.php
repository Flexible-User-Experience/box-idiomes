<?php

namespace AppBundle\Form\Model;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * Class GenerateReceiptModel.
 *
 * @category FormModel
 */
class GenerateReceiptModel extends AbstractGenerateReceiptInvoiceModel
{
    /**
     * @var GenerateReceiptItemModel[]
     */
    private $items;

    /**
     * Methods.
     */

    /**
     * GenerateReceiptModel constructor.
     */
    public function __construct()
    {
        $this->items = new ArrayCollection();
    }

    /**
     * @return GenerateReceiptItemModel[]
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * @param GenerateReceiptItemModel[] $items
     *
     * @return $this
     */
    public function setItems($items)
    {
        $this->items = $items;

        return $this;
    }

    /**
     * @param GenerateReceiptItemModel $item
     *
     * @return $this
     */
    public function addItem(GenerateReceiptItemModel $item)
    {
        $this->items->add($item);

        return $this;
    }

    /**
     * @param GenerateReceiptItemModel $item
     *
     * @return $this
     */
    public function removeItem(GenerateReceiptItemModel $item)
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
