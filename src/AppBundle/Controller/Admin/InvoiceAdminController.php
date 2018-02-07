<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\Invoice;
use AppBundle\Form\Type\GenerateInvoiceType;
use Symfony\Component\HttpFoundation\File\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class InvoiceAdminController.
 *
 * @category Controller
 */
class InvoiceAdminController extends BaseAdminController
{
    /**
     * Generate invoice action.
     *
     * @param Request         $request
     *
     * @return Response
     *
     * @throws NotFoundHttpException If the object does not exist
     * @throws AccessDeniedException If access is not granted
     */
    public function generateAction(Request $request = null)
    {
        $object = new Invoice();
        $form = $this->createForm(GenerateInvoiceType::class);
        $form->handleRequest($request);

        $student = [];
        if ($form->isSubmitted() && $form->isValid()) {
            // TODO some logic
            $student = $this->get('app.student_repository')->findAll();
            $this->addFlash('success', 'Les factures han estat generades correctament.');

        }

        return $this->render(
            '::Admin/Invoice/generate_invoice_form.html.twig',
            array(
                'action'   => 'generate',
                'object'   => $object,
                'form'     => $form->createView(),
                'students' => $student,
            ),
            null,
            $request
        );
    }
}
