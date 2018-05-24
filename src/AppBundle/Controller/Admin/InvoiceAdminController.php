<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Form\Model\GenerateInvoiceModel;
use AppBundle\Form\Type\GenerateInvoiceType;
use AppBundle\Form\Type\GenerateInvoiceYearMonthChooserType;
use AppBundle\Manager\GenerateInvoiceFormManager;
use AppBundle\Repository\StudentRepository;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Bundle\FrameworkBundle\Translation\Translator;
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
     * @param Request $request
     *
     * @return Response
     *
     * @throws NotFoundHttpException    If the object does not exist
     * @throws AccessDeniedException    If access is not granted
     * @throws NonUniqueResultException If problem with unique entities
     */
    public function generateAction(Request $request = null)
    {
        /** @var StudentRepository $sr */
        $sr = $this->container->get('app.student_repository');

        /** @var GenerateInvoiceFormManager $gifm */
        $gifm = $this->container->get('app.generate_invoice_form_manager');

        // year & month chooser form
        $generateInvoiceYearMonthChooser = new GenerateInvoiceModel();
        /** @var Controller $this */
        $yearMonthForm = $this->createForm(GenerateInvoiceYearMonthChooserType::class, $generateInvoiceYearMonthChooser);
        $yearMonthForm->handleRequest($request);

        // build items form
        $generateInvoice = new GenerateInvoiceModel();
        /** @var Controller $this */
        $form = $this->createForm(GenerateInvoiceType::class, $generateInvoice);
        $form->handleRequest($request);

        // emtpy students found
        $students = [];

        if ($yearMonthForm->isSubmitted() && $yearMonthForm->isValid()) {
            $year = $generateInvoiceYearMonthChooser->getYear();
            $month = $generateInvoiceYearMonthChooser->getMonth();
            // fill students found
            $students = $sr->getStudentsInEventsByYearAndMonthSortedBySurnameWithValidTariff($year, $month);
            // fill full items form
            $generateInvoice = $gifm->buildFullModelForm($year, $month);
            /** @var Controller $this */
            $form = $this->createForm(GenerateInvoiceType::class, $generateInvoice);
        }

        return $this->renderWithExtraParams(
            '::Admin/Invoice/generate_invoice_form.html.twig',
            array(
                'action' => 'generate',
                'year_month_form' => $yearMonthForm->createView(),
                'form' => $form->createView(),
                'students' => $students,
            )
        );
    }

    /**
     * Generate invoice action.
     *
     * @param Request $request
     *
     * @return Response
     *
     * @throws NotFoundHttpException                 If the object does not exist
     * @throws AccessDeniedException                 If access is not granted
     * @throws NonUniqueResultException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function creatorAction(Request $request = null)
    {
        /** @var GenerateInvoiceFormManager $gifm */
        $gifm = $this->container->get('app.generate_invoice_form_manager');
        $generateInvoice = $gifm->transformRequestArrayToModel($request->get('generate_invoice'));
        $recordsParsed = $gifm->persistFullModelForm($generateInvoice);

        /** @var Translator $translator */
        $translator = $this->container->get('translator.default');
        if (0 === $recordsParsed) {
            $this->addFlash('warning', $translator->trans('backend.admin.invoice.generator.no_records_presisted'));
        } else {
            $this->addFlash('success', $translator->trans('backend.admin.invoice.generator.flash_success', array('%amount%' => $recordsParsed), 'messages'));
        }

        return $this->redirectToList();
    }

    /**
     * Generate PDF invoice action.
     *
     * @return Response
     *
     * @throws NotFoundHttpException If the object does not exist
     * @throws AccessDeniedException If access is not granted
     */
    public function pdfAction()
    {
        /* @var Controller $this */
        $this->addFlash('danger', 'Aquesta funcionalitat encara no està disponible. No s\'ha generat cap factura amb PDF.');

        return $this->redirectToList();
    }

    /**
     * Send PDF invoice action.
     *
     * @return Response
     *
     * @throws NotFoundHttpException If the object does not exist
     * @throws AccessDeniedException If access is not granted
     */
    public function sendAction()
    {
        /* @var Controller $this */
        $this->addFlash('danger', 'Aquesta funcionalitat encara no està disponible. No s\'ha enviat cap factura per email.');

        return $this->redirectToList();
    }
}
