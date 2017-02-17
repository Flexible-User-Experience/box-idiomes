<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class NewsletterContact
 *
 * @category Entity
 * @package  AppBundle\Entity
 * @author   David Romaní <david@flux.cat>
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AppBundle\Repository\NewsletterContactRepository")
 */
class NewsletterContact extends AbstractBase
{

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     */
    private $email;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean")
     */
    private $checked = false;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean")
     */
    private $answered = false;


    /**
     *
     *
     * Methods
     *
     *
     */

    /**
     * Set Email
     *
     * @param string $email
     *
     * @return $this
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get Email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set Checked
     *
     * @param boolean $checked
     *
     * @return $this
     */
    public function setChecked($checked)
    {
        $this->checked = $checked;

        return $this;
    }

    /**
     * Get Checked
     *
     * @return boolean
     */
    public function getChecked()
    {
        return $this->checked;
    }

    /**
     * Set Answered
     *
     * @param boolean $answered
     *
     * @return $this
     */
    public function setAnswered($answered)
    {
        $this->answered = $answered;

        return $this;
    }

    /**
     * Get Answered
     *
     * @return boolean
     */
    public function getAnswered()
    {
        return $this->answered;
    }
}
