<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Class ClassGroup.
 *
 * @category Entity
 *
 * @author   Wils Iglesias <wiglesias83@gmail.com>
 *
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ClassGroupRepository")
 * @ORM\Table(name="class_group")
 * @UniqueEntity({"code"})
 */
class ClassGroup extends AbstractBase
{
    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $code;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $book;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $color;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean", nullable=true, options={"default"=0})
     */
    private $isForPrivateLessons;

    /**
     * Method.
     */

    /**
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param string $code
     *
     * @return ClassGroup
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return ClassGroup
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getBook()
    {
        return $this->book;
    }

    /**
     * @param string $book
     *
     * @return ClassGroup
     */
    public function setBook($book)
    {
        $this->book = $book;

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
     * @return ClassGroup
     */
    public function setColor($color)
    {
        $this->color = $color;

        return $this;
    }

    /**
     * @return bool
     */
    public function isForPrivateLessons()
    {
        return $this->isForPrivateLessons;
    }

    /**
     * @return bool
     */
    public function getIsForPrivateLessons()
    {
        return $this->isForPrivateLessons();
    }

    /**
     * @param bool $isForPrivateLessons
     *
     * @return $this
     */
    public function setIsForPrivateLessons($isForPrivateLessons)
    {
        $this->isForPrivateLessons = $isForPrivateLessons;

        return $this;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->id ? $this->getCode().($this->name ? ' · '.$this->getName() : '') : '---';
    }
}
