<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Class StudentAbsence.
 *
 * @category Entity
 *
 * @ORM\Entity(repositoryClass="AppBundle\Repository\StudentAbsenceRepository")
 * @ORM\Table(name="student_absence")
 * @UniqueEntity({"student", "day"})
 */
class StudentAbsence extends AbstractBase
{
    /**
     * @var Student
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Student")
     */
    private $student;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="date", nullable=false)
     */
    private $day;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean", nullable=true, options={"default"=0})
     */
    private $hasBeenNotified = false;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $notificationDate;

    /**
     * Methods.
     */

    /**
     * @return Student
     */
    public function getStudent()
    {
        return $this->student;
    }

    /**
     * @param Student $student
     *
     * @return $this
     */
    public function setStudent($student)
    {
        $this->student = $student;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getDay()
    {
        return $this->day;
    }

    /**
     * @return string
     */
    public function getDayString()
    {
        return $this->getDay()->format('d/m/Y');
    }

    /**
     * @param \DateTime $day
     *
     * @return $this
     */
    public function setDay(\DateTime $day)
    {
        $this->day = $day;

        return $this;
    }

    /**
     * @return string
     */
    public function getCalendarTitle()
    {
        return '[Alumne] '.$this->getStudent()->getName();
    }

    /**
     * @return bool
     */
    public function isHasBeenNotified()
    {
        return $this->hasBeenNotified;
    }

    /**
     * @return bool
     */
    public function getHasBeenNotified()
    {
        return $this->isHasBeenNotified();
    }

    /**
     * @return bool
     */
    public function hasBeenNotified()
    {
        return $this->isHasBeenNotified();
    }

    /**
     * @param bool $hasBeenNotified
     *
     * @return $this
     */
    public function setHasBeenNotified($hasBeenNotified)
    {
        $this->hasBeenNotified = $hasBeenNotified;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getNotificationDate()
    {
        return $this->notificationDate;
    }

    /**
     * @param \DateTime $notificationDate
     *
     * @return $this
     */
    public function setNotificationDate($notificationDate)
    {
        $this->notificationDate = $notificationDate;

        return $this;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->id ? $this->getDay()->format('d/m/Y').' · '.$this->getStudent() : '---';
    }
}
