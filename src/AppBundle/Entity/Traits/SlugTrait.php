<?php

namespace AppBundle\Entity\Traits;

/**
 * Slug trait
 *
 * @category Trait
 * @package  AppBundle\Entity\Traits
 * @author   David Romaní <david@flux.cat>
 */
Trait SlugTrait
{
    /**
     * Set slug
     *
     * @param string $slug
     *
     * @return $this
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }
}
