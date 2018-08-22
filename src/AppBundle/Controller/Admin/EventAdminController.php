<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\Event;
use AppBundle\Form\Type\EventType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
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

        /** @var Controller $this */
        $form = $this->createForm(EventType::class, $object);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // TODO edit events batch

            /* @var Controller $this */
            $this->addFlash('success', 'S\'han modificat X esdeveniments');

            return $this->redirectToList();
        }

        return $this->renderWithExtraParams(
            '::Admin/Event/batch_edit_form.html.twig',
            array(
                'action' => 'batchedit',
                'object' => $object,
                'form' => $form->createView(),
            )
        );
    }
}
