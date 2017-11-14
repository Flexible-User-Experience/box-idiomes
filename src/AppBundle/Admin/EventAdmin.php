<?php

namespace AppBundle\Admin;

use AppBundle\Entity\Event;
use AppBundle\Enum\EventClassroomTypeEnum;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\CoreBundle\Form\Type\DateTimePickerType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

/**
 * Class EventAdmin.
 *
 * @category Admin
 *
 * @author   Wils Iglesias <wiglesias83@gmail.com>
 */
class EventAdmin extends AbstractBaseAdmin
{
    protected $classnameLabel = 'Event';
    protected $baseRoutePattern = 'classrooms/event';
    protected $datagridValues = array(
        '_sort_by' => 'begin',
        '_sort_order' => 'desc',
    );

    /**
     * Configure route collection.
     *
     * @param RouteCollection $collection
     */
    protected function configureRoutes(RouteCollection $collection)
    {
        parent::configureRoutes($collection);
        $collection
            ->remove('delete');
    }

    /**
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->with('backend.admin.general', $this->getFormMdSuccessBoxArray(4))
            ->add(
                'begin',
                DateTimePickerType::class,
                array(
                    'label' => 'backend.admin.event.begin',
                    'format' => 'd/M/y H:m',
                    'required' => true,
                )
            )
            ->add(
                'end',
                DateTimePickerType::class,
                array(
                    'label' => 'backend.admin.event.end',
                    'format' => 'd/M/y H:m',
                    'required' => true,
                )
            );
        if (is_null($this->getSubject()->getId())) {
            $formMapper
                ->add(
                    'dayFrequencyRepeat',
                    null,
                    array(
                        'label' => 'backend.admin.event.dayFrequencyRepeat',
                        'required' => false,
                        'help' => 'backend.admin.event.dayFrequencyRepeat_help',
                    )
                )
                ->add(
                    'until',
                    DateTimePickerType::class,
                    array(
                        'label' => 'backend.admin.event.until',
                        'format' => 'd/M/y H:m',
                        'required' => false,
                    )
                );
        }
        $formMapper
            ->end()
            ->with('backend.admin.controls', $this->getFormMdSuccessBoxArray(3))
            ->add(
                'teacher',
                EntityType::class,
                array(
                    'label' => 'backend.admin.event.teacher',
                    'required' => true,
                    'class' => 'AppBundle:Teacher',
                    'choice_label' => 'name',
                    'query_builder' => $this->getConfigurationPool()->getContainer()->get('app.teacher_repository')->getEnabledSortedByNameQB(),
                )
            )

            ->add(
                'group',
                EntityType::class,
                array(
                    'label' => 'backend.admin.event.group',
                    'required' => true,
                    'class' => 'AppBundle:ClassGroup',
                    'query_builder' => $this->getConfigurationPool()->getContainer()->get('app.class_group_repository')->getEnabledSortedByCodeQB(),
                )
            )
            ->add(
                'students',
                EntityType::class,
                array(
                    'label' => 'backend.admin.event.students',
                    'required' => false,
                    'multiple' => true,
                    'class' => 'AppBundle:Student',
                    'choice_label' => 'fullCanonicalName',
                    'query_builder' => $this->getConfigurationPool()->getContainer()->get('app.student_repository')->getEnabledSortedBySurnameQB(),
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
            ->end();
    }

    /**
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add(
                'begin',
                'doctrine_orm_date',
                array(
                    'label' => 'backend.admin.event.begin',
                    'field_type' => 'sonata_type_datetime_picker',
                )
            )
            ->add(
                'end',
                'doctrine_orm_date',
                array(
                    'label' => 'backend.admin.event.end',
                    'field_type' => 'sonata_type_datetime_picker',
                )
            )
            ->add(
                'teacher',
                null,
                array(
                    'label' => 'backend.admin.event.teacher',
                )
            )
            ->add(
                'classroom',
                null,
                array(
                    'label' => 'backend.admin.event.classroom',
                ),
                ChoiceType::class,
                array(
                    'expanded' => false,
                    'multiple' => false,
                    'choices' => EventClassroomTypeEnum::getEnumArray(),
                )
            )
            ->add(
                'group',
                null,
                array(
                    'label' => 'backend.admin.event.group',
                )
            )
            ->add(
                'students',
                null,
                array(
                    'label' => 'backend.admin.event.students',
                )
            )
        ;
    }

    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        unset($this->listModes['mosaic']);
        $listMapper
            ->add(
                'begin',
                'date',
                array(
                    'label' => 'backend.admin.event.begin',
                    'format' => 'd/m/Y H:i',
                    'editable' => true,
                )
            )
            ->add(
                'end',
                'date',
                array(
                    'label' => 'backend.admin.event.end',
                    'format' => 'd/m/Y H:i',
                    'editable' => true,
                )
            )
            ->add(
                'teacher',
                null,
                array(
                    'label' => 'backend.admin.event.teacher',
                    'editable' => false,
                    'associated_property' => 'name',
                    'sortable' => true,
                    'sort_field_mapping' => array('fieldName' => 'name'),
                    'sort_parent_association_mappings' => array(array('fieldName' => 'teacher')),
                )
            )
            ->add(
                'classroom',
                null,
                array(
                    'label' => 'backend.admin.event.classroom',
                    'template' => '::Admin/Cells/list__cell_classroom_type.html.twig',
                )
            )
            ->add(
                'group',
                null,
                array(
                    'label' => 'backend.admin.event.group',
                    'editable' => true,
                    'associated_property' => 'code',
                    'sortable' => true,
                    'sort_field_mapping' => array('fieldName' => 'code'),
                    'sort_parent_association_mappings' => array(array('fieldName' => 'group')),
                )
            )
            ->add(
                '_action',
                'actions',
                array(
                    'actions' => array(
                        'edit' => array('template' => '::Admin/Buttons/list__action_edit_button.html.twig'),
                    ),
                    'label' => 'backend.admin.actions',
                )
            );
    }

    /**
     * @param Event $object
     */
    public function postPersist($object)
    {
        if ($object->getDayFrequencyRepeat() && $object->getUntil()) {
            $em = $this->getConfigurationPool()->getContainer()->get('doctrine')->getManager();
            $currentBegin = $object->getBegin();
            $currentEnd = $object->getEnd();
            $currentBegin->add(new \DateInterval('P'.$object->getDayFrequencyRepeat().'D'));
            $currentEnd->add(new \DateInterval('P'.$object->getDayFrequencyRepeat().'D'));
            $previousEvent = $object;
            $found = false;

            while ($currentBegin->format('Y-m-d H:i') <= $object->getUntil()->format('Y-m-d H:i')) {
                $event = new Event();
                $event
                    ->setBegin($currentBegin)
                    ->setEnd($currentEnd)
                    ->setTeacher($previousEvent->getTeacher())
                    ->setClassroom($previousEvent->getClassroom())
                    ->setGroup($previousEvent->getGroup())
                    ->setStudents($previousEvent->getStudents())
                    ->setPrevious($previousEvent)
                ;

                $em->persist($event);
                $em->flush();

                $currentBegin->add(new \DateInterval('P'.$object->getDayFrequencyRepeat().'D'));
                $currentEnd->add(new \DateInterval('P'.$object->getDayFrequencyRepeat().'D'));
                $previousEvent = $event;
                $found = true;
            }

            if ($found) {
                $previousEvent = $event->getPrevious();
                while (!is_null($previousEvent)) {
                    $previousEvent->setNext($event);
                    $em->flush();
                    $event = $previousEvent;
                    $previousEvent = $previousEvent->getPrevious();
                }
            }
        }
    }
}
