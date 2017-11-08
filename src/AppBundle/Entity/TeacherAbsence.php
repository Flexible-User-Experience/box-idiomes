<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Class TeacherAbsence.
 *
 * @category Entity
 *
 * @author   Wils Iglesias <wiglesias83@gmail.com>
 *
 * @ORM\Entity(repositoryClass="AppBundle\Repository\TeacherAbsenceRepository")
 * @ORM\Table(name="teacher_absence")
 * @UniqueEntity({"teacher", "day"})
 */
class TeacherAbsence extends AbstractBase
{
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
    private $type = 0;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="date")
     */
    private $day;

    /**
     * Methods.
     */

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
     * @return TeacherAbsence
     */
    public function setTeacher($teacher)
    {
        $this->teacher = $teacher;

        return $this;
    }

    /**
     * @return int
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param int $type
     *
     * @return TeacherAbsence
     */
    public function setType($type)
    {
        $this->type = $type;

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
     * @param \DateTime $day
     *
     * @return TeacherAbsence
     */
    public function setDay(\DateTime $day)
    {
        $this->day = $day;

        return $this;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->id ? $this->getDay()->format('d/m/Y').' · '.$this->getType().' · '.$this->getTeacher() : '---';
    }
}
