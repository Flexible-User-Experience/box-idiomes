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
 * Class Teacher.
 *
 * @category    Entity
 *
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
     * @Assert\Image(allowLandscape=false, allowPortrait=true, minWidth=600)
     */
    private $imageFile;

    /**
     * @var int
     *
     * @ORM\Column(type="integer", options={"default"=0})
     */
    private $color = 0;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean")
     */
    private $showInHomepage = true;

    /**
     * Methods.
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
        return 'c-'.TeacherColorEnum::getEnumArray()[$this->getColor()];
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

    /**
     * @return bool
     */
    public function isShowInHomepage()
    {
        return $this->showInHomepage;
    }

    /**
     * @param bool $showInHomepage
     *
     * @return Teacher
     */
    public function setShowInHomepage($showInHomepage)
    {
        $this->showInHomepage = $showInHomepage;

        return $this;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->id ? $this->getName() : '---';
    }
}
