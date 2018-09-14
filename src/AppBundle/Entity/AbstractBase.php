<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Abstract entities base class.
 *
 * @category Entity
 *
 * @Gedmo\SoftDeleteable(fieldName="removedAt", timeAware=false)
 */
abstract class AbstractBase
{
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    protected $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     * @Gedmo\Timestampable(on="create")
     */
    protected $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime", nullable=true)
     * @Gedmo\Timestampable(on="update")
     */
    protected $updatedAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $removedAt;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean")
     */
    protected $enabled = true;

    /**
     * Methods.
     */

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param \DateTime $createdAt
     *
     * @return $this
     */
    public function setCreatedAt(\DateTime $createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @return string
     */
    public function getCreatedAtString()
    {
        return $this->getCreatedAt()->format('d/m/Y');
    }

    /**
     * @param \DateTime $updatedAt
     *
     * @return $this
     */
    public function setUpdatedAt(\DateTime $updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @return string
     */
    public function getUpdatedAtString()
    {
        return $this->getUpdatedAt()->format('d/m/Y');
    }

    /**
     * @param \DateTime $removedAt
     *
     * @return $this
     */
    public function setRemovedAt(\DateTime $removedAt)
    {
        $this->removedAt = $removedAt;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getRemovedAt()
    {
        return $this->removedAt;
    }

    /**
     * @return string
     */
    public function getRemovedAtString()
    {
        return $this->getRemovedAt()->format('d/m/Y');
    }

    /**
     * Remove (soft delete).
     *
     * @return $this
     */
    public function remove()
    {
        $this->setRemovedAt(new \DateTime());

        return $this;
    }

    /**
     * Is deleted?
     *
     * @return bool
     */
    public function isDeleted()
    {
        return null !== $this->removedAt;
    }

    /**
     * @param bool $enabled
     *
     * @return $this
     */
    public function setEnabled($enabled)
    {
        $this->enabled = $enabled;

        return $this;
    }

    /**
     * @return bool
     */
    public function getEnabled()
    {
        return $this->enabled;
    }

    /**
     * @return bool
     */
    public function isEnabled()
    {
        return $this->getEnabled();
    }

    /**
     * To string.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->id ? $this->getId().' · '.$this->getCreatedAt()->format('d/m/Y') : '---';
    }
}
