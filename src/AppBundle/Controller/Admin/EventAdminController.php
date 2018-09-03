<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\Event;
use AppBundle\Form\Type\EventBatchRemoveType;
use AppBundle\Form\Type\EventType;
use AppBundle\Manager\EventManager;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\File\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class EventAdminController.
 *
 * @category Controller
 */
class EventAdminController extends BaseAdminController
{
    /**
     * @param null|int|string $id
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function editAction($id = null)
    {
        $request = $this->getRequest();
        $id = $request->get($this->admin->getIdParameter());

        /** @var Event $object */
        $object = $this->admin->getObject($id);

        if (!$object) {
            throw $this->createNotFoundException(sprintf('unable to find the object with id: %s', $id));
        }

        if (!$object->getEnabled()) {
            throw $this->createNotFoundException(sprintf('unable to find the object with id : %s', $id));
        }

        return parent::editAction($id);
    }

    /**
     * Edit event and all the next related events action.
     *
     * @param Request $request
     *
     * @return Response
     *
     * @throws NotFoundHttpException If the object does not exist
     * @throws AccessDeniedException If access is not granted
     * @throws \Exception
     */
    public function batcheditAction(Request $request)
    {
        $request = $this->resolveRequest($request);
        $id = $request->get($this->admin->getIdParameter());

        /** @var Event $object */
        $object = $this->admin->getObject($id);

        if (!$object) {
            throw $this->createNotFoundException(sprintf('unable to find the object with id : %s', $id));
        }

        if (!$object->getEnabled()) {
            throw $this->createNotFoundException(sprintf('unable to find the object with id : %s', $id));
        }

        /** @var EventManager $eventsManager */
        $eventsManager = $this->container->get('app.event_manager');
        $firstEvent = $eventsManager->getFirstEventOf($object);
        if (is_null($firstEvent)) {
            $firstEvent = $object;
        }
        $lastEvent = $eventsManager->getLastEventOf($object);

        /** @var Form $form */
        $form = $this->createForm(EventType::class, $object, array('event' => $object));
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $eventIdStopRangeIterator = $form->get('range')->getData();
            /** @var EntityManager $em */
            $em = $this->get('doctrine')->getManager();
            $em->flush();
            $iteratorCounter = 1;
            if (!is_null($object->getNext())) {
                $iteratedEvent = $object;
                while (!is_null($iteratedEvent->getNext())) {
                    $currentBegin = $iteratedEvent->getBegin();
                    $currentEnd = $iteratedEvent->getEnd();
                    $currentBegin->add(new \DateInterval('P'.$firstEvent->getDayFrequencyRepeat().'D'));
                    $currentEnd->add(new \DateInterval('P'.$firstEvent->getDayFrequencyRepeat().'D'));
                    $iteratedEvent = $iteratedEvent->getNext();
                    if ($iteratedEvent->getId() <= $eventIdStopRangeIterator) {
                        $iteratedEvent
                            ->setBegin($currentBegin)
                            ->setEnd($currentEnd)
                            ->setTeacher($object->getTeacher())
                            ->setClassroom($object->getClassroom())
                            ->setGroup($object->getGroup())
                            ->setStudents($object->getStudents());
                        $em->flush();
                        ++$iteratorCounter;
                    }
                }
            }
            $this->addFlash(
                'success',
                'S\'han modificat '.$iteratorCounter.' esdeveniments del calendari d\'horaris correctament.'
            );

            return $this->redirectToList();
        }

        return $this->renderWithExtraParams(
            '::Admin/Event/batch_edit_form.html.twig',
            array(
                'action' => 'batchedit',
                'object' => $object,
                'firstEvent' => $firstEvent,
                'lastEvent' => $lastEvent,
                'progressBarPercentiles' => $eventsManager->getProgressBarPercentilesOf($object),
                'form' => $form->createView(),
            )
        );
    }

    /**
     * Delete event and all the next related events action.
     *
     * @param Request $request
     *
     * @return Response
     *
     * @throws NotFoundHttpException If the object does not exist
     * @throws AccessDeniedException If access is not granted
     * @throws \Exception
     */
    public function batchdeleteAction(Request $request)
    {
        $request = $this->resolveRequest($request);
        $id = $request->get($this->admin->getIdParameter());

        /** @var Event $object */
        $object = $this->admin->getObject($id);

        if (!$object) {
            throw $this->createNotFoundException(sprintf('unable to find the object with id : %s', $id));
        }

        if (!$object->getEnabled()) {
            throw $this->createNotFoundException(sprintf('unable to find the object with id : %s', $id));
        }

        /** @var EventManager $eventsManager */
        $eventsManager = $this->container->get('app.event_manager');
        $firstEvent = $eventsManager->getFirstEventOf($object);
        $lastEvent = $eventsManager->getLastEventOf($object);

        /** @var Form $form */
        $form = $this->createForm(EventBatchRemoveType::class, $object, array('event' => $object));
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var EntityManager $em */
            $em = $this->get('doctrine')->getManager();
            $eventIdStopRange = $form->get('range')->getData();

            /** @var Event|null $eventStopRange */
            $eventStopRange = $em->getRepository('AppBundle:Event')->find($eventIdStopRange);

            /** @var Event|null $eventAfterStopRange */
            $eventAfterStopRange = null;
            if (!is_null($eventStopRange->getNext())) {
                $eventAfterStopRange = $em->getRepository('AppBundle:Event')->find($eventStopRange->getNext()->getId());
            }

            /** @var Event|null $eventBeforeStartRange */
            $eventBeforeStartRange = null;
            if (!is_null($object->getPrevious())) {
                $eventBeforeStartRange = $em->getRepository('AppBundle:Event')->find($object->getPrevious()->getId());
            }

            // begin range
            if (is_null($firstEvent)) {
                $iteratorCounter = 1;
                if (!is_null($object->getNext())) {
                    $iteratedEvent = $object;
                    while (!is_null($iteratedEvent->getNext())) {
                        $iteratedEvent = $iteratedEvent->getNext();
                        if ($iteratedEvent->getId() <= $eventIdStopRange) {
                            $iteratedEvent->setEnabled(false);
                            ++$iteratorCounter;
                        }
                    }
                    $object->setEnabled(false);
                    if (!is_null($eventAfterStopRange)) {
                        $eventAfterStopRange->setPrevious(null);
                    }
                    $em->flush();
                }

                // end range
            } elseif (is_null($eventAfterStopRange)) {
                $iteratorCounter = 1;
                $iteratedEvent = $object;
                while (!is_null($iteratedEvent->getNext())) {
                    $iteratedEvent = $iteratedEvent->getNext();
                    if ($iteratedEvent->getId() <= $eventIdStopRange) {
                        $iteratedEvent->setEnabled(false);
                        ++$iteratorCounter;
                    }
                }
                $object->setEnabled(false);
                if (!is_null($eventBeforeStartRange)) {
                    $eventBeforeStartRange->setNext(null);
                }
                $em->flush();

            // middle range
            } else {
                if (is_null($eventBeforeStartRange)) {
                    $eventBeforeStartRange = $firstEvent;
                }
                if (is_null($eventAfterStopRange)) {
                    $eventAfterStopRange = $lastEvent;
                }

                $eventBeforeStartRange->setNext($eventAfterStopRange);
                $eventAfterStopRange->setPrevious($eventBeforeStartRange);
                $em->flush();

                $iteratorCounter = 1;

                if (!is_null($object->getNext())) {
                    $iteratedEvent = $object;
                    while (!is_null($iteratedEvent->getNext())) {
                        $iteratedEvent = $iteratedEvent->getNext();
                        if ($iteratedEvent->getId() <= $eventIdStopRange) {
                            $iteratedEvent->setEnabled(false);
                            ++$iteratorCounter;
                        }
                    }
                    $object->setEnabled(false);
                    $em->flush();
                }
            }

            $this->addFlash(
                'success',
                'S\'han esborrat '.$iteratorCounter.' esdeveniments del calendari d\'horaris correctament.'
            );

            return $this->redirectToList();
        }

        return $this->renderWithExtraParams(
            '::Admin/Event/batch_delete_form.html.twig',
            array(
                'action' => 'batchdelete',
                'object' => $object,
                'firstEvent' => $firstEvent,
                'lastEvent' => $lastEvent,
                'progressBarPercentiles' => $eventsManager->getProgressBarPercentilesOf($object),
                'form' => $form->createView(),
            )
        );
    }
}
