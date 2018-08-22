<?php

namespace AppBundle\Form\Type;

use AppBundle\Entity\ClassGroup;
use AppBundle\Entity\Event;
use AppBundle\Entity\Student;
use AppBundle\Entity\Teacher;
use AppBundle\Enum\EventClassroomTypeEnum;
use AppBundle\Repository\ClassGroupRepository;
use AppBundle\Repository\StudentRepository;
use AppBundle\Repository\TeacherRepository;
use Sonata\CoreBundle\Form\Type\DateTimePickerType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
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
     * @var TeacherRepository
     */
    private $tr;

    /**
     * @var ClassGroupRepository
     */
    private $cgr;

    /**
     * @var StudentRepository
     */
    private $sr;

    /**
     * Methods.
     */

    /**
     * EventType constructor.
     *
     * @param TeacherRepository    $tr
     * @param ClassGroupRepository $cgr
     * @param StudentRepository    $sr
     */
    public function __construct(TeacherRepository $tr, ClassGroupRepository $cgr, StudentRepository $sr)
    {
        $this->tr = $tr;
        $this->cgr = $cgr;
        $this->sr = $sr;
    }

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
                'teacher',
                EntityType::class,
                array(
                    'label' => 'backend.admin.event.teacher',
                    'required' => true,
                    'class' => Teacher::class,
                    'choice_label' => 'name',
                    'query_builder' => $this->tr->getEnabledSortedByNameQB(),
                )
            )
            ->add(
                'group',
                EntityType::class,
                array(
                    'label' => 'backend.admin.event.group',
                    'required' => true,
                    'class' => ClassGroup::class,
                    'query_builder' => $this->cgr->getEnabledSortedByCodeQB(),
                )
            )
            ->add(
                'students',
                EntityType::class,
                array(
                    'label' => 'backend.admin.event.students',
                    'required' => false,
                    'multiple' => true,
                    'class' => Student::class,
                    'choice_label' => 'fullCanonicalName',
                    'query_builder' => $this->sr->getEnabledSortedBySurnameQB(),
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
