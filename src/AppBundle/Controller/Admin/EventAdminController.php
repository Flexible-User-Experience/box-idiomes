<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\Event;
use AppBundle\Form\Type\EventType;
use AppBundle\Manager\EventManager;
use Sonata\AdminBundle\Controller\CRUDController as Controller;
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
            /* @var Controller $this */
            throw $this->createNotFoundException(sprintf('unable to find the object with id : %s', $id));
        }

        /** @var EventManager $eventsManager */
        $eventsManager = $this->container->get('app.event_manager');
        $firstEvent = $eventsManager->getFirstEventOf($object);
        $lastEvent = $eventsManager->getLastEventOf($object);

        /** @var Controller $this */
        $form = $this->createForm(EventType::class, $object);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Controller $this */
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
                    $iteratedEvent
                        ->setBegin($currentBegin)
                        ->setEnd($currentEnd)
                        ->setTeacher($object->getTeacher())
                        ->setClassroom($object->getClassroom())
                        ->setGroup($object->getGroup())
                        ->setStudents($object->getStudents())
                    ;
                    $em->flush();
                    ++$iteratorCounter;
                }
            }
            /* @var Controller $this */
            $this->addFlash('success', 'S\'han modificat '.$iteratorCounter.' esdeveniments del calendari d\'horaris correctament.');

            return $this->redirectToList();
        }

        return $this->renderWithExtraParams(
            '::Admin/Event/batch_edit_form.html.twig',
            array(
                'action' => 'batchedit',
                'object' => $object,
                'firstEvent' => $firstEvent,
                'lastEvent' => $lastEvent,
                'form' => $form->createView(),
            )
        );
    }
}
