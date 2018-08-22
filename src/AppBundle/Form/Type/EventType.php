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
use Symfony\Component\Form\FormBuilderInterface;
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
                'range',
                ChoiceType::class,
                array(
                    'mapped' => false,
                    'label' => 'backend.admin.event.range',
                    'required' => true,
                    'choices' => array(
                        1 => '21/08/2018',
                        3 => '22/08/2018',
                        5 => '23/08/2018',
                        6 => '24/08/2018',
                        2 => '25/08/2018',
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
