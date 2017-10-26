<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Student.
 *
 * @category Entity
 *
 * @author   Wils Iglesias <wiglesias83@gmail.com>
 *
 * @ORM\Entity(repositoryClass="AppBundle\Repository\StudentRepository")
 * @ORM\Table(name="student")
 * @UniqueEntity({"name", "surname"})
 */
class Student extends AbstractBase
{
    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $surname;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="date")
     */
    private $birthDate;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $ownMobile;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $contactPhone;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $contactName;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $contactDni;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $parentAddress;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     * @Assert\Email(strict=true, checkMX=true, checkHost=true)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $address;

    /**
     * @var int
     *
     * @ORM\Column(type="integer", options={"default"=0})
     */
    private $payment = 0;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $bankAccountName;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     * @Assert\Iban()
     */
    private $bankAccountNumber;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $schedule;

    /**
     * @var string
     *
     * @ORM\Column(type="text", length=4000, nullable=true)
     */
    private $comments;

    /**
     * @var City
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\City")
     */
    private $city;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean")
     */
    private $showSEPAequalAddress;

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
     * @return Student
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getSurname()
    {
        return $this->surname;
    }

    /**
     * @param string $surname
     *
     * @return Student
     */
    public function setSurname($surname)
    {
        $this->surname = $surname;

        return $this;
    }

    /**
     * @return string
     */
    public function getFullName()
    {
        return $this->name.' '.$this->surname;
    }

    /**
     * @return \DateTime
     */
    public function getBirthDate()
    {
        return $this->birthDate;
    }

    /**
     * @param \DateTime $birthDate
     *
     * @return Student
     */
    public function setBirthDate(\DateTime $birthDate)
    {
        $this->birthDate = $birthDate;

        return $this;
    }

    /**
     * @return int
     */
    public function getYearsOld()
    {
        $today = new \DateTime();
        $interval = $today->diff($this->birthDate);

        return $interval->y;
    }

    /**
     * @return string
     */
    public function getOwnMobile()
    {
        return $this->ownMobile;
    }

    /**
     * @param string $ownMobile
     *
     * @return Student
     */
    public function setOwnMobile($ownMobile)
    {
        $this->ownMobile = $ownMobile;

        return $this;
    }

    /**
     * @return string
     */
    public function getContactPhone()
    {
        return $this->contactPhone;
    }

    /**
     * @param string $contactPhone
     *
     * @return Student
     */
    public function setContactPhone($contactPhone)
    {
        $this->contactPhone = $contactPhone;

        return $this;
    }

    /**
     * @return string
     */
    public function getContactName()
    {
        return $this->contactName;
    }

    /**
     * @param string $contactName
     *
     * @return Student
     */
    public function setContactName($contactName)
    {
        $this->contactName = $contactName;

        return $this;
    }

    /**
     * @return string
     */
    public function getContactDni()
    {
        return $this->contactDni;
    }

    /**
     * @param string $contactDni
     *
     * @return Student
     */
    public function setContactDni($contactDni)
    {
        $this->contactDni = $contactDni;

        return $this;
    }

    /**
     * @return string
     */
    public function getParentAddress()
    {
        return $this->parentAddress;
    }

    /**
     * @param string $parentAddress
     *
     * @return Student
     */
    public function setParentAddress($parentAddress)
    {
        $this->parentAddress = $parentAddress;

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
     * @return Student
     */
    public function setEmail($email)
    {
        $this->email = $email;

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
     * @return Student
     */
    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * @return float
     */
    public function getPayment()
    {
        return $this->payment;
    }

    /**
     * @param float $payment
     *
     * @return Student
     */
    public function setPayment($payment)
    {
        $this->payment = $payment;

        return $this;
    }

    /**
     * @return string
     */
    public function getBankAccountName()
    {
        return $this->bankAccountName;
    }

    /**
     * @param string $bankAccountName
     *
     * @return Student
     */
    public function setBankAccountName($bankAccountName)
    {
        $this->bankAccountName = $bankAccountName;

        return $this;
    }

    /**
     * @return string
     */
    public function getBankAccountNumber()
    {
        return $this->bankAccountNumber;
    }

    /**
     * @return bool|string
     */
    public function getBAN1part()
    {
        if (strlen($this->bankAccountNumber) < 4) {
            return '';
        }

        return substr($this->bankAccountNumber, 0, 4);
    }

    /**
     * @return bool|string
     */
    public function getBAN2part()
    {
        if (strlen($this->bankAccountNumber) < 8) {
            return '';
        }

        return substr($this->bankAccountNumber, 4, 4);
    }

    /**
     * @return bool|string
     */
    public function getBAN3part()
    {
        if (strlen($this->bankAccountNumber) < 12) {
            return '';
        }

        return substr($this->bankAccountNumber, 8, 4);
    }

    /**
     * @return bool|string
     */
    public function getBAN4part()
    {
        if (strlen($this->bankAccountNumber) < 16) {
            return '';
        }

        return substr($this->bankAccountNumber, 12, 4);
    }

    /**
     * @return bool|string
     */
    public function getBAN5part()
    {
        if (strlen($this->bankAccountNumber) < 20) {
            return '';
        }

        return substr($this->bankAccountNumber, 16, 4);
    }

    /**
     * @return bool|string
     */
    public function getBAN6part()
    {
        if (strlen($this->bankAccountNumber) < 24) {
            return '';
        }

        return substr($this->bankAccountNumber, 20, 4);
    }

    /**
     * @param string $bankAccountNumber
     *
     * @return Student
     */
    public function setBankAccountNumber($bankAccountNumber)
    {
        $this->bankAccountNumber = $bankAccountNumber;

        return $this;
    }

    /**
     * @return string
     */
    public function getSchedule()
    {
        return $this->schedule;
    }

    /**
     * @param string $schedule
     *
     * @return Student
     */
    public function setSchedule($schedule)
    {
        $this->schedule = $schedule;

        return $this;
    }

    /**
     * @return string
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * @param string $comments
     *
     * @return Student
     */
    public function setComments($comments)
    {
        $this->comments = $comments;

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
     * @return Student
     */
    public function setCity($city)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * @return bool
     */
    public function isShowSEPAequalAddress()
    {
        return $this->showSEPAequalAddress;
    }

    /**
     * @param bool $showSEPAequalAddress
     *
     * @return Student
     */
    public function setShowSEPAequalAddress($showSEPAequalAddress)
    {
        $this->showSEPAequalAddress = $showSEPAequalAddress;

        return $this;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->id ? $this->getFullName() : '---';
    }
}
