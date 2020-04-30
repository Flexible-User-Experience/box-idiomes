<?php

namespace AppBundle\Model;

/**
 * Dummy class File to use as a faked entity. (Only for FileManager integration backend pourpose).
 *
 * @category Entity
 */
class FileDummy
{
    /**
     * @var string
     */
    private $name;

    /**
     * Methods.
     */

    /**
     * FileDummy constructor.
     *
     * @param $name
     */
    public function __construct($name)
    {
        $this->name = $name;
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
     * @return FileDummy
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->name ? $this->getName() : '---';
    }
}
