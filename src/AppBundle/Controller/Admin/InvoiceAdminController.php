<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Form\Model\GenerateInvoiceModel;
use AppBundle\Form\Type\GenerateInvoiceType;
use AppBundle\Form\Type\GenerateInvoiceYearMonthChooserType;
use AppBundle\Manager\GenerateInvoiceFormManager;
use AppBundle\Repository\StudentRepository;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
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

        // full items form
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
            $students = $sr->getStudentsInEventsByYearAndMonthSortedBySurname($year, $month);
            // fill full items form
            $generateInvoice = $gifm->buildFullModelForm($year, $month);
            /** @var Controller $this */
            $form = $this->createForm(GenerateInvoiceType::class, $generateInvoice);

            // preview invoices action
//            if ($form->get('preview')->isClicked()) {
//                if (0 == count($students)) {
//                    $hideGenerateSubmitButton = true;
//                } else {
//                    $generateInvoice->setItems($formGeneratorService->buildFormItems($year, $month));
//                    $form = $this->createForm(GenerateInvoiceType::class, $generateInvoice);
//                }
//            }
            // generate invoices action
            /*if ($form->get('generate')->isClicked()) {
                $translator = $this->container->get('translator.default');
                /** @var EntityManager $em *
                $em = $this->get('doctrine')->getManager();
                /** @var Student $student *
                foreach ($students as $student) {
                    $invoiceLine = new InvoiceLine();
                    $invoiceLine
                        ->setStudent($student)
                        ->setDescription($translator->trans('backend.admin.invoiceLine.generator.line', array('%month%' => InvoiceYearMonthEnum::getTranslatedMonthEnumArray()[$month], '%year%' => $year), 'messages'))
                        ->setUnits(1)
                        ->setPriceUnit($student->getTariff()->getPrice())
                        ->setDiscount($student->calculateMonthlyDiscount())
                        ->setTotal($invoiceLine->calculateBaseAmount())
                    ;
                    $invoice = new Invoice();
                    $invoice
                        ->setStudent($student)
                        ->setPerson($student->getParent() ? $student->getParent() : null)
                        ->setDate(new \DateTime())
                        ->setIsPayed(false)
                        ->setYear($year)
                        ->setMonth($month)
                        ->addLine($invoiceLine)
                        ->setIrpf($invoice->calculateIrpf())
                        ->setTaxParcentage(0)
                        ->setTotalAmount($invoiceLine->getTotal() - $invoice->getIrpf())
                    ;
                    $em->persist($invoice);
                }
                $em->flush();
                $this->addFlash('success', $translator->trans('backend.admin.invoice.generator.flash_success', array('%amount%' => count($students)), 'messages'));

                return $this->redirectToList('admin_app_invoice_list');
            }*/
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $translator = $this->container->get('translator.default');
            $this->addFlash('success', $translator->trans('backend.admin.invoice.generator.flash_success', array('%amount%' => count($students)), 'messages'));

            return $this->redirectToList('admin_app_invoice_list');
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
