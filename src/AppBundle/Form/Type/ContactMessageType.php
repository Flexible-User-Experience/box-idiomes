<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class ContactMessageType
 *
 * @category FormType
 * @package  AppBundle\Form\Type
 * @author   Anton Serra <aserratorta@gmail.com>
 */
class ContactMessageType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'name',
                TextType::class,
                array(
                    'label'       => false,
                    'required'    => true,
                    'attr'        => array(
                        'placeholder' => 'frontend.forms.name',
                        'class'       => 'common-fields'
                    ),
                    'constraints' => array(
                        new Assert\NotBlank(),
                    ),
                )
            )
            ->add(
                'email',
                EmailType::class,
                array(
                    'label'       => false,
                    'required'    => true,
                    'attr'        => array(
                        'placeholder' => 'frontend.forms.email',
                        'class'       => 'common-fields'
                    ),
                    'constraints' => array(
                        new Assert\NotBlank(),
                        new Assert\Email(array(
                            'strict'    => true,
                            'checkMX'   => true,
                            'checkHost' => true,
                        )),
                    ),
                )
            )
            ->add(
                'phone',
                TextType::class,
                array(
                    'label'    => false,
                    'required' => false,
                    'attr'     => array(
                        'placeholder' => 'frontend.forms.phone',
                        'class'       => 'common-fields'
                    ),
                )
            )
            ->add(
                'message',
                TextareaType::class,
                array(
                    'label'       => 'frontend.forms.message',
                    'required'    => true,
                    'attr'        => array(
                        'rows'        => 5,
                        'class'       => 'message-field'
                    ),
                    'constraints' => array(
                        new Assert\NotBlank(),
                    ),
                )
            )
            ->add(
                'send',
                SubmitType::class,
                array(
                    'label' => 'frontend.forms.send',
                    'attr'  => array(
                        'class' => 'btn-kowo',
                    ),
                )
            );
    }

    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'contact_message';
    }
}
