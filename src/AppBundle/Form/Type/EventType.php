<?php

namespace AppBundle\Form\Type;

use AppBundle\Entity\Event;
use AppBundle\Enum\EventClassroomTypeEnum;
use Sonata\CoreBundle\Form\Type\DateTimePickerType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class EventType.
 *
 * @category FormType
 */
class EventType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'begin',
                DateTimePickerType::class,
                array(
                    'label' => 'backend.admin.event.begin',
                    'format' => 'd/M/y H:mm',
                    'required' => true,
                )
            )
            ->add(
                'end',
                DateTimePickerType::class,
                array(
                    'label' => 'backend.admin.event.end',
                    'format' => 'd/M/y H:mm',
                    'required' => true,
                )
            )
            ->add(
                'dayFrequencyRepeat',
                IntegerType::class,
                array(
                    'label' => 'backend.admin.event.dayFrequencyRepeat',
                    'required' => false,
                    'disabled' => true,
                )
            )
            ->add(
                'until',
                DateTimePickerType::class,
                array(
                    'label' => 'backend.admin.event.until',
                    'format' => 'd/M/y H:mm',
                    'disabled' => true,
                    'required' => false,
                )
            )
            ->add(
                'classroom',
                ChoiceType::class,
                array(
                    'label' => 'backend.admin.event.classroom',
                    'choices' => EventClassroomTypeEnum::getEnumArray(),
                    'multiple' => false,
                    'expanded' => false,
                    'required' => true,
                )
            )
            ->add(
                'send',
                SubmitType::class,
                array(
                    'label' => 'backend.admin.submit',
                    'attr' => array(
                        'class' => 'btn-primary',
                    ),
                )
            )
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => Event::class,
            )
        );
    }
}
