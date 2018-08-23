<?php

namespace AppBundle\Form\Type;

use AppBundle\Entity\Event;
use AppBundle\Manager\EventManager;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class EventBatchRemoveType.
 *
 * @category FormType
 */
class EventBatchRemoveType extends AbstractType
{
    /**
     * @var EventManager
     */
    private $em;

    /**
     * Methods.
     */

    /**
     * EventBatchRemoveType constructor.
     *
     * @param EventManager $em
     */
    public function __construct(EventManager $em)
    {
        $this->em = $em;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /** @var Event $event */
        $event = $options['event'];
        /** @var Event $lastEvent */
        $lastEvent = $this->em->getLastEventOf($event);
        $builder
            ->add(
                'range',
                ChoiceType::class,
                array(
                    'mapped' => false,
                    'label' => 'backend.admin.event.batch_delete.range',
                    'required' => true,
                    'choices' => $this->em->getInclusiveRangeChoices($event),
                    'data' => is_null($lastEvent) ? $event->getId() : $this->em->getLastEventOf($event)->getId(),
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
                'event' => null,
            )
        );
    }
}
