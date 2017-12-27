<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

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
     * @ORM\Column(type="datetime")
     */
    private $begin;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
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
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Student")
     * @ORM\JoinTable(name="events_students",
     *      joinColumns={@ORM\JoinColumn(name="event_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="student_id", referencedColumnName="id")}
     * )
     */
    private $students;

    /**
     * @var Event
     *
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\Event", cascade={"persist"})
     * @ORM\JoinColumn(name="previous_id", referencedColumnName="id")
     */
    private $previous;

    /**
     * @var Event
     *
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\Event", cascade={"persist"})
     * @ORM\JoinColumn(name="next_id", referencedColumnName="id")
     */
    private $next;

    /**
     * @var int
     *
     * @ORM\Column(type="integer", nullable=true)
     * @Assert\GreaterThanOrEqual(1)
     */
    private $dayFrequencyRepeat;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $until;

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

    /**
     * @param Student $student
     *
     * @return $this
     */
    public function addStudent(Student $student)
    {
        if (!$this->students->contains($student)) {
            $this->students->add($student);
        }

        return $this;
    }

    /**
     * @param Student $student
     *
     * @return $this
     */
    public function removeStudent(Student $student)
    {
        if ($this->students->contains($student)) {
            $this->students->removeElement($student);
        }

        return $this;
    }

    /**
     * @return Event
     */
    public function getPrevious()
    {
        return $this->previous;
    }

    /**
     * @param Event $previous
     *
     * @return Event
     */
    public function setPrevious($previous)
    {
        $this->previous = $previous;

        return $this;
    }

    /**
     * @return Event
     */
    public function getNext()
    {
        return $this->next;
    }

    /**
     * @param Event $next
     *
     * @return Event
     */
    public function setNext($next)
    {
        $this->next = $next;

        return $this;
    }

    /**
     * @return int
     */
    public function getDayFrequencyRepeat()
    {
        return $this->dayFrequencyRepeat;
    }

    /**
     * @param int|null $dayFrequencyRepeat
     *
     * @return Event
     */
    public function setDayFrequencyRepeat($dayFrequencyRepeat)
    {
        $this->dayFrequencyRepeat = $dayFrequencyRepeat;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getUntil()
    {
        return $this->until;
    }

    /**
     * @param \DateTime|null $until
     *
     * @return Event
     */
    public function setUntil($until)
    {
        $this->until = $until;

        return $this;
    }

    /**
     * @Assert\Callback
     *
     * @param ExecutionContextInterface $context
     */
    public function validateEnd(ExecutionContextInterface $context)
    {
        if ($this->getEnd() < $this->getBegin()) {
            $context
                ->buildViolation('La data final ha de ser més gran que la data d\'inici')
                ->atPath('end')
                ->addViolation();
        }
    }

    /**
     * @Assert\Callback
     *
     * @param ExecutionContextInterface $context
     */
    public function validateUntil(ExecutionContextInterface $context)
    {
        if (!is_null($this->getUntil()) && $this->getUntil() < $this->getEnd()) {
            $context
                ->buildViolation('La data de repeteció final ha de ser més gran que la data final')
                ->atPath('until')
                ->addViolation();
        }
    }
}
