<?php

namespace AppBundle\Entity;

use AncaRebeca\FullCalendarBundle\Model\EventInterface;
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
class Event extends AbstractBase implements EventInterface
{
    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $title;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean", options={"default"=0})
     */
    private $allDay = false;

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
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $url;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $className;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean", options={"default"=0})
     */
    private $editable = false;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean", options={"default"=0})
     */
    private $startEditable = false;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean", options={"default"=0})
     */
    private $durationEditable = false;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $rendering;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean", options={"default"=0})
     */
    private $overlap = true;

    /**
     * @var int
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    private $constraint;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $source;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $color;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $backgroundColor;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $textColor;

    /**
     * @var array
     */
    private $customFields = array();

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
     *
     * @param string|null    $title
     * @param \DateTime|null $start
     */
    public function __construct($title = null, \DateTime $start = null)
    {
        $this->title = $title;
        $this->begin = $start;
        $this->students = new ArrayCollection();
    }

    /**
     * @param int $id
     *
     * @return Event
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->getGroup()->getCode().' '.$this->getGroup()->getBook();
    }

    /**
     * @param string $title
     *
     * @return Event
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return bool
     */
    public function isAllDay()
    {
        return $this->allDay;
    }

    /**
     * @return bool
     */
    public function getAllDay()
    {
        return $this->allDay;
    }

    /**
     * @param bool $allDay
     *
     * @return Event
     */
    public function setAllDay($allDay)
    {
        $this->allDay = $allDay;

        return $this;
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
    public function getStartDate()
    {
        return $this->begin;
    }

    /**
     * @param \DateTime $begin
     *
     * @return Event
     */
    public function setStartDate(\DateTime $begin)
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
     * @return \DateTime
     */
    public function getEndDate()
    {
        return $this->end;
    }

    /**
     * @param \DateTime $end
     *
     * @return Event
     */
    public function setEndDate(\DateTime $end)
    {
        $this->end = $end;

        return $this;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param string $url
     *
     * @return Event
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * @return string
     */
    public function getClassName()
    {
        return $this->className;
    }

    /**
     * @param string $className
     *
     * @return Event
     */
    public function setClassName($className)
    {
        $this->className = $className;

        return $this;
    }

    /**
     * @return bool
     */
    public function isEditable()
    {
        return $this->editable;
    }

    /**
     * @return bool
     */
    public function getEditable()
    {
        return $this->editable;
    }

    /**
     * @param bool $editable
     *
     * @return Event
     */
    public function setEditable($editable)
    {
        $this->editable = $editable;

        return $this;
    }

    /**
     * @return bool
     */
    public function isStartEditable()
    {
        return $this->startEditable;
    }

    /**
     * @return bool
     */
    public function getStartEditable()
    {
        return $this->startEditable;
    }

    /**
     * @param bool $startEditable
     *
     * @return Event
     */
    public function setStartEditable($startEditable)
    {
        $this->startEditable = $startEditable;

        return $this;
    }

    /**
     * @return bool
     */
    public function isDurationEditable()
    {
        return $this->durationEditable;
    }

    /**
     * @return bool
     */
    public function getDurationEditable()
    {
        return $this->durationEditable;
    }

    /**
     * @param bool $durationEditable
     *
     * @return Event
     */
    public function setDurationEditable($durationEditable)
    {
        $this->durationEditable = $durationEditable;

        return $this;
    }

    /**
     * @return string
     */
    public function getRendering()
    {
        return $this->rendering;
    }

    /**
     * @param string $rendering
     *
     * @return Event
     */
    public function setRendering($rendering)
    {
        $this->rendering = $rendering;

        return $this;
    }

    /**
     * @return bool
     */
    public function isOverlap()
    {
        return $this->overlap;
    }

    /**
     * @return bool
     */
    public function getOverlap()
    {
        return $this->overlap;
    }

    /**
     * @param bool $overlap
     *
     * @return Event
     */
    public function setOverlap($overlap)
    {
        $this->overlap = $overlap;

        return $this;
    }

    /**
     * @return int
     */
    public function getConstraint()
    {
        return $this->constraint;
    }

    /**
     * @param int $constraint
     *
     * @return Event
     */
    public function setConstraint($constraint)
    {
        $this->constraint = $constraint;

        return $this;
    }

    /**
     * @return string
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * @param string $source
     *
     * @return Event
     */
    public function setSource($source)
    {
        $this->source = $source;

        return $this;
    }

    /**
     * @return string
     */
    public function getColor()
    {
        return $this->color;
    }

    /**
     * @param string $color
     *
     * @return Event
     */
    public function setColor($color)
    {
        $this->color = $color;

        return $this;
    }

    /**
     * @return string
     */
    public function getBackgroundColor()
    {
        return $this->backgroundColor;
    }

    /**
     * @param string $backgroundColor
     *
     * @return Event
     */
    public function setBackgroundColor($backgroundColor)
    {
        $this->backgroundColor = $backgroundColor;

        return $this;
    }

    /**
     * @return string
     */
    public function getTextColor()
    {
        return $this->textColor;
    }

    /**
     * @param string $textColor
     *
     * @return Event
     */
    public function setTextColor($textColor)
    {
        $this->textColor = $textColor;

        return $this;
    }

    /**
     * @param $name
     * @param $value
     *
     * @return mixed
     */
    public function setCustomField($name, $value)
    {
        $this->customFields[$name] = $value;
    }

    /**
     * @param $name
     *
     * @return mixed
     */
    public function getCustomFieldValue($name)
    {
        return $this->customFields[$name];
    }

    /**
     * @return array
     */
    public function getCustomFields()
    {
        return $this->customFields;
    }

    /**
     * @param $name
     *
     * @return mixed
     */
    public function removeCustomField($name)
    {
        if (!isset($this->customFields[$name]) && !array_key_exists($name, $this->customFields)) {
            return null;
        }

        $removed = $this->customFields[$name];
        unset($this->customFields[$name]);

        return $removed;
    }

    /**
     * @return Teacher
     */
    public function getTeacher()
    {
        return $this->teacher;
    }

    /**
     * @param Teacher $teacher
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
                ->buildViolation('La data ha de ser més gran que la data d\'inici')
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
        if ($this->getUntil() < $this->getEnd()) {
            $context
                ->buildViolation('La data ha de ser més gran que la data final')
                ->atPath('until')
                ->addViolation();
        }
    }

    /**
     * @return array
     */
    public function toArray()
    {
        $event = [];

        $event['title'] = $this->getTitle();
        $event['start'] = $this->getStartDate()->format("Y-m-d\TH:i:sP");
        $event['allDay'] = $this->isAllDay();
        $event['editable'] = $this->isEditable();
        $event['startEditable'] = $this->isStartEditable();
        $event['durationEditable'] = $this->isDurationEditable();
        $event['overlap'] = $this->isOverlap();

        if (null !== $this->getId()) {
            $event['id'] = $this->getId();
        }

        if (null !== $this->getUrl()) {
            $event['url'] = $this->getUrl();
        }

        if (null !== $this->getBackgroundColor()) {
            $event['backgroundColor'] = $this->getBackgroundColor();
        }

        if (null !== $this->getTextColor()) {
            $event['textColor'] = $this->getTextColor();
        }

        if (null !== $this->getClassName()) {
            $event['className'] = $this->getClassName();
        }

        if (null !== $this->getEndDate()) {
            $event['end'] = $this->getEndDate()->format("Y-m-d\TH:i:sP");
        }

        if (null !== $this->getRendering()) {
            $event['rendering'] = $this->getRendering();
        }

        if (null !== $this->getConstraint()) {
            $event['constraint'] = $this->getConstraint();
        }

        if (null !== $this->getSource()) {
            $event['source'] = $this->getSource();
        }

        if (null !== $this->getColor()) {
            $event['color'] = $this->getColor();
        }

        foreach ($this->getCustomFields() as $field => $value) {
            $event[$field] = $value;
        }

        return $event;
    }
}
