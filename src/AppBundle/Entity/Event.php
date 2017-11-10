<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Event.
 *
 * @category Entity
 *
 * @author   Wils Iglesias <wiglesias83@gmail.com>
 *
 * @ORM\Entity(repositoryClass="AppBundle\Repository\EventRepository")
 * @ORM\Table(name="event")
 */
class Event extends AbstractBase
{
    /**
     * @var \DateTime
     *
     * @ORM\Column(type="date")
     */
    private $begin;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="date")
     */
    private $end;

    /**
     * @var Teacher
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Teacher")
     */
    private $teacher;

    /**
     * @var int
     *
     * @ORM\Column(type="integer", options={"default"=0})
     */
    private $classroom = 0;

    /**
     * @var ClassGroup
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\ClassGroup")
     */
    private $group;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Student")
     */
    private $students;

    /**
     * Methods.
     */

    /**
     * Event constructor.
     */
    public function __construct()
    {
        $this->students = new ArrayCollection();
    }

    /**
     * @return \DateTime
     */
    public function getBegin()
    {
        return $this->begin;
    }

    /**
     * @param \DateTime $begin
     *
     * @return Event
     */
    public function setBegin(\DateTime $begin)
    {
        $this->begin = $begin;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getEnd()
    {
        return $this->end;
    }

    /**
     * @param \DateTime $end
     *
     * @return Event
     */
    public function setEnd(\DateTime $end)
    {
        $this->end = $end;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getTeacher()
    {
        return $this->teacher;
    }

    /**
     * @param mixed $teacher
     *
     * @return Event
     */
    public function setTeacher($teacher)
    {
        $this->teacher = $teacher;

        return $this;
    }

    /**
     * @return int
     */
    public function getClassroom()
    {
        return $this->classroom;
    }

    /**
     * @param int $classroom
     *
     * @return Event
     */
    public function setClassroom($classroom)
    {
        $this->classroom = $classroom;

        return $this;
    }

    /**
     * @return ClassGroup
     */
    public function getGroup()
    {
        return $this->group;
    }

    /**
     * @param ClassGroup $group
     *
     * @return Event
     */
    public function setGroup($group)
    {
        $this->group = $group;

        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getStudents()
    {
        return $this->students;
    }

    /**
     * @param ArrayCollection $students
     *
     * @return Event
     */
    public function setStudents($students)
    {
        $this->students = $students;

        return $this;
    }
}
