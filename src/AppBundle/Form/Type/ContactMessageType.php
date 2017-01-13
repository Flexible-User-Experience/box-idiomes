<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class ContactMessageType
 *
 * @category FormType
 * @package  AppBundle\Form\Type
 * @author   Anton Serra <aserratorta@gmail.com>
 */
class ContactMessageType extends ContactMessageAnswerType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

    }

    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'contact_message';
    }
}
