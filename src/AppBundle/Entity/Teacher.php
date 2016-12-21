<?php

namespace AppBundle\Entity;

use AppBundle\Entity\Traits\DescriptionTrait;
use AppBundle\Entity\Traits\ImageTrait;
use AppBundle\Entity\Traits\PositionTrait;
use AppBundle\Entity\Traits\SlugTrait;

/**
 * Class Teacher
 *
 * @category    Entity
 * @package     AppBundle\Entity
 * @author      Anton Serra <aserratorta@gmail.com>
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AppBundle\Repository\TeacherRepository")
 */
class Teacher extends AbstractBase
{
    use DescriptionTrait;
    use ImageTrait;
    use PositionTrait;
    use SlugTrait;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     */
    private $slug;

    /**
     *
     *
     * Methods
     *
     *
     */

    /**
     * @return string
     */
    public function getName()
    {        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }
}