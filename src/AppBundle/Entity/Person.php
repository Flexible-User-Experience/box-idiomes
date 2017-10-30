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
    protected $dni;

    /**
     * @var array
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Student")
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
