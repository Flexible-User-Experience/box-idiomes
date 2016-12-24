<?php

namespace AppBundle\Entity;

use AppBundle\Entity\Traits\DescriptionTrait;
use AppBundle\Entity\Traits\ImageTrait;
use AppBundle\Entity\Traits\PositionTrait;
use AppBundle\Entity\Traits\SlugTrait;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Service
 *
 * @category    Entity
 * @package     AppBundle\Entity
 * @author      Anton Serra <aserratorta@gmail.com>
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ServiceRepository")
 */
class Service extends AbstractBase
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
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true, length=255)
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
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     *
     * @return $this
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }
}