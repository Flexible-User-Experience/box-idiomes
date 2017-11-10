<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

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
class Student extends AbstractPerson
{
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
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Person")
     */
    private $parent;

    /**
     * @var Bank
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Bank", cascade={"persist"})
     */
    protected $bank;

    /**
     * @var Event
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Event")
     */
    private $event;

    /**
     * Methods.
     */

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
}
