<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Class Student.
 *
 * @category Entity
 *
 * @ORM\Entity(repositoryClass="AppBundle\Repository\StudentRepository")
 * @ORM\Table(name="student")
 * @UniqueEntity({"name", "surname"})
 */
class Student extends AbstractPerson
{
    const DISCOUNT_PER_EXTRA_SON = 5;

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
    private $schedule;

    /**
     * @var string
     *
     * @ORM\Column(type="text", length=4000, nullable=true)
     */
    private $comments;

    /**
     * @var Person
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Person", inversedBy="students")
     */
    private $parent;

    /**
     * @var Bank
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Bank", cascade={"persist"})
     */
    protected $bank;

    /**
     * @var Tariff
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Tariff")
     * @ORM\JoinColumn(name="tariff_id", referencedColumnName="id")
     */
    private $tariff;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Event", mappedBy="students")
     */
    private $events;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean", nullable=true, options={"default"=0})
     */
    private $hasImageRightsAccepted = false;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean", nullable=true, options={"default"=0})
     */
    private $hasSepaAgreementAccepted = false;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean", nullable=true, options={"default"=0})
     */
    private $isPaymentExempt = false;

    /**
     * Methods.
     */

    /**
     * Student constructor.
     */
    public function __construct()
    {
        $this->events = new ArrayCollection();
    }

    /**
     * @return \DateTime
     */
    public function getBirthDate()
    {
        return $this->birthDate;
    }

    /**
     * @return string
     */
    public function getBirthDateString()
    {
        return $this->getBirthDate()->format('d/m/Y');
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
     *
     * @throws \Exception
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
     * @return Person
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * @param Person $parent
     *
     * @return Student
     */
    public function setParent($parent)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * @return Tariff
     */
    public function getTariff()
    {
        return $this->tariff;
    }

    /**
     * @param Tariff $tariff
     *
     * @return Student
     */
    public function setTariff($tariff)
    {
        $this->tariff = $tariff;

        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getEvents()
    {
        return $this->events;
    }

    /**
     * @param ArrayCollection $events
     *
     * @return Student
     */
    public function setEvents($events)
    {
        $this->events = $events;

        return $this;
    }

    /**
     * @param Event $event
     */
    public function addEvent(Event $event)
    {
        if (!$this->events->contains($event)) {
            $this->events->add($event);
        }
    }

    /**
     * @param Event $event
     */
    public function removeEvent(Event $event)
    {
        if ($this->events->contains($event)) {
            $this->events->removeElement($event);
        }
    }

    /**
     * @return bool
     */
    public function isHasImageRightsAccepted()
    {
        return $this->hasImageRightsAccepted;
    }

    /**
     * @return bool
     */
    public function getHasImageRightsAccepted()
    {
        return $this->isHasImageRightsAccepted();
    }

    /**
     * @param bool $hasImageRightsAccepted
     *
     * @return $this
     */
    public function setHasImageRightsAccepted($hasImageRightsAccepted)
    {
        $this->hasImageRightsAccepted = $hasImageRightsAccepted;

        return $this;
    }

    /**
     * @return bool
     */
    public function isHasSepaAgreementAccepted()
    {
        return $this->hasSepaAgreementAccepted;
    }

    /**
     * @return bool
     */
    public function getHasSepaAgreementAccepted()
    {
        return $this->isHasSepaAgreementAccepted();
    }

    /**
     * @param bool $hasSepaAgreementAccepted
     *
     * @return $this
     */
    public function setHasSepaAgreementAccepted($hasSepaAgreementAccepted)
    {
        $this->hasSepaAgreementAccepted = $hasSepaAgreementAccepted;

        return $this;
    }

    /**
     * @return bool
     */
    public function isPaymentExempt()
    {
        return $this->isPaymentExempt;
    }

    /**
     * @return bool
     */
    public function getIsPaymentExempt()
    {
        return $this->isPaymentExempt();
    }

    /**
     * @param bool $isPaymentExempt
     *
     * @return $this
     */
    public function setIsPaymentExempt($isPaymentExempt)
    {
        $this->isPaymentExempt = $isPaymentExempt;

        return $this;
    }

    /**
     * @return float
     */
    public function calculateMonthlyTariff()
    {
        $price = $this->getTariff()->getPrice();
        if ($this->getParent()) {
            $enabledSonsAmount = $this->getParent()->getEnabledSonsAmount();
            $discount = $enabledSonsAmount ? ((($enabledSonsAmount - 1) * self::DISCOUNT_PER_EXTRA_SON) / $enabledSonsAmount) : 0;
            $price = $price - $discount;
        }

        return $price;
    }

    /**
     * @return float|int
     */
    public function calculateMonthlyDiscount()
    {
        $discount = 0;
        if ($this->getParent()) {
            $enabledSonsAmount = $this->getParent()->getEnabledSonsAmount();
            $discount = $enabledSonsAmount ? round(($enabledSonsAmount - 1) * self::DISCOUNT_PER_EXTRA_SON / $enabledSonsAmount, 2) : 0;
        }

        return $discount;
    }

    /**
     * @return bool
     */
    public function hasDiscount()
    {
        if ($this->getParent()) {
            return $this->getParent()->getEnabledSonsAmount() > 1 ? true : false;
        }

        return false;
    }

    /**
     * @return string
     */
    public function getMainEmailSubject()
    {
        $email = $this->getEmail();
        if ($this->getParent() && $this->getParent()->getEmail()) {
            $email = $this->getParent()->getEmail();
        }

        return $email;
    }
}
