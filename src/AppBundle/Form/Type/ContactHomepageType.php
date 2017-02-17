<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class ContactHomepageType
 *
 * @category FormType
 * @package  AppBundle\Form\Type
 * @author   Wilson Iglesias <wiglesias83@gmail.com>
 */
class ContactHomepageType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'email',
                EmailType::class,
                array(
                    'required' => false,
                    'attr'  => array(
                        'placeholder' => 'frontend.forms.email',
                        'class' => 'newsletter-email'
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
                'send',
                SubmitType::class,
                array(
                    'label' => 'frontend.forms.subscribe',
                    'attr'  => array(
                        'class' => 'btn-newsletter',
                    ),
                )
            );
    }

    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'contact_homepage';
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'AppBundle\Entity\NewsletterContact',
            )
        );
    }
}
