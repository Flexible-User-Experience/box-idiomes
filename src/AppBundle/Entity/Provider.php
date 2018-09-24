<?php

namespace AppBundle\Entity;

use AppBundle\Enum\StudentPaymentEnum;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Provider.
 *
 * @category Entity
 *
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ProviderRepository")
 * @UniqueEntity({"tic"})
 */
class Provider extends AbstractBase
{
    /**
     * @var string Tax Identification Number
     *
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private $tic;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $alias;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $address;

    /**
     * @var City
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\City")
     */
    private $city;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $phone;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255, nullable=true)
     * @Assert\Email(strict=true, checkMX=true, checkHost=true)
     */
    private $email;

    /**
     * @var int
     *
     * @ORM\Column(type="integer", options={"default"=0})
     */
    private $paymentMethod;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $ibanForBankDraftPayment;

    /**
     * Methods.
     */

    /**
     * @return string
     */
    public function getTic()
    {
        return $this->tic;
    }

    /**
     * @param string $tic
     *
     * @return $this
     */
    public function setTic($tic)
    {
        $this->tic = $tic;

        return $this;
    }

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
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getAlias()
    {
        return $this->alias;
    }

    /**
     * @param string $alias
     *
     * @return $this
     */
    public function setAlias($alias)
    {
        $this->alias = $alias;

        return $this;
    }

    /**
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @param string $address
     *
     * @return $this
     */
    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * @return City
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @param City $city
     *
     * @return $this
     */
    public function setCity($city)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @param string $phone
     *
     * @return $this
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $email
     *
     * @return $this
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return int
     */
    public function getPaymentMethod()
    {
        return $this->paymentMethod;
    }

    /**
     * @return string
     */
    public function getPaymentString()
    {
        return StudentPaymentEnum::getEnumTranslatedArray()[$this->getPaymentMethod()];
    }

    /**
     * @param int $paymentMethod
     *
     * @return $this
     */
    public function setPaymentMethod($paymentMethod)
    {
        $this->paymentMethod = $paymentMethod;

        return $this;
    }

    /**
     * @return string
     */
    public function getIbanForBankDraftPayment()
    {
        return $this->ibanForBankDraftPayment;
    }

    /**
     * @param string $ibanForBankDraftPayment
     *
     * @return $this
     */
    public function setIbanForBankDraftPayment($ibanForBankDraftPayment)
    {
        $this->ibanForBankDraftPayment = $ibanForBankDraftPayment;

        return $this;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->id ? ($this->alias ? $this->getName().' · '.$this->getAlias().' · '.$this->getTic() : $this->getName().' · '.$this->getTic()) : '---';
    }
}
