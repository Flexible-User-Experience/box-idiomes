<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Student
 *
 * @category Entity
 * @author   Wils Iglesias <wiglesias83@gmail.com>
 *
 * @ORM\Entity(repositoryClass="AppBundle\Repository\StudentRepository")
 * @ORM\Table(name="student")
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
     * @var \DateTime
     *
     * @ORM\Column(type="date")
     */
    private $brithDate;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $ownMobile;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $phoneContact;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $nameContact;

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
     * @var float
     *
     * @ORM\Column(type="float", nullable=true)
     */
    private $payment;

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
     * @return Student
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getBrithDate()
    {
        return $this->brithDate;
    }

    /**
     * @param \DateTime $brithDate
     * @return Student
     */
    public function setBrithDate(\DateTime $brithDate)
    {
        $this->brithDate = $brithDate;
        return $this;
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
    public function getPhoneContact()
    {
        return $this->phoneContact;
    }

    /**
     * @param string $phoneContact
     * @return Student
     */
    public function setPhoneContact($phoneContact)
    {
        $this->phoneContact = $phoneContact;
        return $this;
    }

    /**
     * @return string
     */
    public function getNameContact()
    {
        return $this->nameContact;
    }

    /**
     * @param string $nameContact
     * @return Student
     */
    public function setNameContact($nameContact)
    {
        $this->nameContact = $nameContact;
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
    public function getSchedule()
    {
        return $this->schedule;
    }

    /**
     * @param string $schedule
     * @return Student
     */
    public function setSchedule($schedule)
    {
        $this->schedule = $schedule;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * @param mixed $comments
     * @return Student
     */
    public function setComments($comments)
    {
        $this->comments = $comments;
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
