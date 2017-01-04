<?php

namespace AppBundle\Entity;

use AppBundle\Entity\Traits\DescriptionTrait;
use AppBundle\Entity\Traits\ImageTrait;
use AppBundle\Entity\Traits\PositionTrait;
use AppBundle\Entity\Traits\SlugTrait;
use AppBundle\Enum\TeacherColorEnum;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * Class Teacher
 *
 * @category    Entity
 * @package     AppBundle\Entity
 * @author      Anton Serra <aserratorta@gmail.com>
 * @ORM\Table()
 * @Vich\Uploadable
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
     * @ORM\Column(type="string", nullable=true, length=255)
     */
    private $slug;

    /**
     * @var File
     *
     * @Vich\UploadableField(mapping="teacher", fileNameProperty="imageName")
     * @Assert\File(
     *     maxSize="10M",
     *     mimeTypes={"image/jpg", "image/jpeg", "image/png", "image/gif"}
     * )
     * @Assert\Image(minWidth=1200)
     */
    private $imageFile;

    /**
     * @var integer
     *
     * @ORM\Column(type="integer", options={"default"=0})
     */
    private $color = 0;

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
    {
        return $this->name;
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

    /**
     * @return int
     */
    public function getColor()
    {
        return $this->color;
    }

    /**
     * @return string
     */
    public function getCssColor()
    {
        if ($this->getColor() === TeacherColorEnum::MAGENTA) {

            return 'c-magenta';
        } else if ($this->getColor() == TeacherColorEnum::BLUE) {

            return 'c-blue';
        }  else if ($this->getColor() == TeacherColorEnum::YELLOW) {

            return 'c-yellow';
        }
        if ($this->getColor() == TeacherColorEnum::GREEN) {

            return 'c-green';
        }

        return $this->color;
    }

    /**
     * @param int $color
     *
     * @return Teacher
     */
    public function setColor($color)
    {
        $this->color = $color;

        return $this;
    }
}