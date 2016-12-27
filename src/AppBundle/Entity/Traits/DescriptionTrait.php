<?php

namespace AppBundle\Entity\Traits;

use Doctrine\ORM\Mapping as ORM;

/**
 * Description trait
 *
 * @category Trait
 * @package  AppBundle\Entity\Traits
 * @author   David Romaní <david@flux.cat>
 */
Trait DescriptionTrait
{
    /**
     * @var string
     *
     * @ORM\Column(type="text", length=4000, nullable=true)
     */
    private $description;

    /**
     * Set description
     *
     * @param string $description
     *
     * @return $this
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }
}
