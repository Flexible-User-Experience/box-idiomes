<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Bank.
 *
 * @category Entity
 *
 * @author   Wils Iglesias <wiglesias83@gmail.com>
 * @ORM\Entity(repositoryClass="AppBundle\Repository\BankRepository")
 * @ORM\Table(name="bank")
 */
class Bank extends AbstractBase
{
    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     * @Assert\Iban()
     */
    private $accountNumber;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $swiftCode;

    /**
     * @var Person
     *
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\Person")
     * @ORM\JoinColumn(name="person_id", referencedColumnName="id")
     */
    private $parent;

    /**
     * Methods.
     */

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return Bank
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getAccountNumber()
    {
        return $this->accountNumber;
    }

    /**
     * @return bool|string
     */
    public function getBAN1part()
    {
        if (strlen($this->accountNumber) < 4) {
            return '';
        }

        return substr($this->accountNumber, 0, 4);
    }

    /**
     * @return bool|string
     */
    public function getBAN2part()
    {
        if (strlen($this->accountNumber) < 8) {
            return '';
        }

        return substr($this->accountNumber, 4, 4);
    }

    /**
     * @return bool|string
     */
    public function getBAN3part()
    {
        if (strlen($this->accountNumber) < 12) {
            return '';
        }

        return substr($this->accountNumber, 8, 4);
    }

    /**
     * @return bool|string
     */
    public function getBAN4part()
    {
        if (strlen($this->accountNumber) < 16) {
            return '';
        }

        return substr($this->accountNumber, 12, 4);
    }

    /**
     * @return bool|string
     */
    public function getBAN5part()
    {
        if (strlen($this->accountNumber) < 20) {
            return '';
        }

        return substr($this->accountNumber, 16, 4);
    }

    /**
     * @return bool|string
     */
    public function getBAN6part()
    {
        if (strlen($this->accountNumber) < 24) {
            return '';
        }

        return substr($this->accountNumber, 20, 4);
    }

    /**
     * @param string $accountNumber
     *
     * @return Bank
     */
    public function setAccountNumber($accountNumber)
    {
        $this->accountNumber = $accountNumber;

        return $this;
    }

    /**
     * @return string
     */
    public function getSwiftCode()
    {
        return $this->swiftCode;
    }

    /**
     * @param string $swiftCode
     *
     * @return Bank
     */
    public function setSwiftCode($swiftCode)
    {
        $this->swiftCode = $swiftCode;

        return $this;
    }

    /**
     * @return Person
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * @param Person $parent
     *
     * @return Bank
     */
    public function setParent($parent)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->id ? $this->getName() : '---';
    }
}
