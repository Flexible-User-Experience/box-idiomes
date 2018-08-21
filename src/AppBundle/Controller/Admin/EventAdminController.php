<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\Event;
use AppBundle\Form\Model\GenerateReceiptModel;
use AppBundle\Form\Type\GenerateReceiptType;
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
     * @param int|string|null $id
     * @param Request         $request
     *
     * @return Response
     *
     * @throws NotFoundHttpException If the object does not exist
     * @throws AccessDeniedException If access is not granted
     */
    public function batcheditAction($id = null, Request $request)
    {
        $request = $this->resolveRequest($request);
        $id = $request->get($this->admin->getIdParameter());

        /** @var Event $object */
        $object = $this->admin->getObject($id);

        if (!$object) {
            throw $this->createNotFoundException(sprintf('unable to find the object with id : %s', $id));
        }

        // build items form
        $generateReceipt = new GenerateReceiptModel();
        /** @var Controller $this */
        $form = $this->createForm(GenerateReceiptType::class, $generateReceipt);
        $form->handleRequest($request);

//        if ($yearMonthForm->isSubmitted() && $yearMonthForm->isValid()) {
//            $year = $generateReceiptYearMonthChooser->getYear();
//            $month = $generateReceiptYearMonthChooser->getMonth();
//            // fill full items form
//            $generateReceipt = $grfm->buildFullModelForm($year, $month);
//            /** @var Controller $this */
//            $form = $this->createForm(GenerateReceiptType::class, $generateReceipt);
//        }

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
