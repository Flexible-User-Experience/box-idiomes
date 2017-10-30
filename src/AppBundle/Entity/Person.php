<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Person.
 *
 * @category Entity
 *
 * @author   Wils Iglesias <wiglesias83@gmail.com>
 *
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PersonRepository")
 * @ORM\Table(name="person")
 */
class Person extends AbstractPerson
{
    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $dni;

    /**
     * @var array
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Student")
     */
    private $students;

    /**
     * Methods.
     */

    /**
     * Person constructor.
     */
    public function __construct()
    {
        $this->students = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function getDni()
    {
        return $this->dni;
    }

    /**
     * @param string $dni
     *
     * @return Person
     */
    public function setDni($dni)
    {
        $this->dni = $dni;

        return $this;
    }

    /**
     * @return array
     */
    public function getStudents()
    {
        return $this->students;
    }

    /**
     * @param array $students
     *
     * @return Person
     */
    public function setStudents($students)
    {
        $this->students = $students;

        return $this;
    }
}
