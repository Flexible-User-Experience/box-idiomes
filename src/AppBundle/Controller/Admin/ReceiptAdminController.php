<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\Invoice;
use AppBundle\Entity\InvoiceLine;
use AppBundle\Entity\Receipt;
use AppBundle\Form\Model\GenerateReceiptModel;
use AppBundle\Form\Type\GenerateReceiptType;
use AppBundle\Form\Type\GenerateReceiptYearMonthChooserType;
use AppBundle\Manager\GenerateReceiptFormManager;
use AppBundle\Service\ReceiptPdfBuilderService;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Bundle\FrameworkBundle\Translation\Translator;
use Symfony\Component\HttpFoundation\File\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class ReceiptAdminController.
 *
 * @category Controller
 */
class ReceiptAdminController extends BaseAdminController
{
    /**
     * Generate receipt action.
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
        /** @var GenerateReceiptFormManager $grfm */
        $grfm = $this->container->get('app.generate_receipt_form_manager');

        // year & month chooser form
        $generateReceiptYearMonthChooser = new GenerateReceiptModel();
        /** @var Controller $this */
        $yearMonthForm = $this->createForm(GenerateReceiptYearMonthChooserType::class, $generateReceiptYearMonthChooser);
        $yearMonthForm->handleRequest($request);

        // build items form
        $generateReceipt = new GenerateReceiptModel();
        /** @var Controller $this */
        $form = $this->createForm(GenerateReceiptType::class, $generateReceipt);
        $form->handleRequest($request);

        if ($yearMonthForm->isSubmitted() && $yearMonthForm->isValid()) {
            $year = $generateReceiptYearMonthChooser->getYear();
            $month = $generateReceiptYearMonthChooser->getMonth();
            // fill full items form
            $generateReceipt = $grfm->buildFullModelForm($year, $month);
            /** @var Controller $this */
            $form = $this->createForm(GenerateReceiptType::class, $generateReceipt);
        }

        return $this->renderWithExtraParams(
            '::Admin/Receipt/generate_receipt_form.html.twig',
            array(
                'action' => 'generate',
                'year_month_form' => $yearMonthForm->createView(),
                'form' => $form->createView(),
                'generate_receipt' => $generateReceipt,
            )
        );
    }

    /**
     * Creator receipt action.
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
        /** @var GenerateReceiptFormManager $grfm */
        $grfm = $this->container->get('app.generate_receipt_form_manager');
        $generateReceipt = $grfm->transformRequestArrayToModel($request->get('generate_receipt'));
        $recordsParsed = $grfm->persistFullModelForm($generateReceipt);

        /** @var Translator $translator */
        $translator = $this->container->get('translator.default');
        if (0 === $recordsParsed) {
            $this->addFlash('warning', $translator->trans('backend.admin.receipt.generator.no_records_presisted'));
        } else {
            $this->addFlash('success', $translator->trans('backend.admin.receipt.generator.flash_success', array('%amount%' => $recordsParsed), 'messages'));
        }

        return $this->redirectToList();
    }

    /**
     * Create Invoice action.
     *
     * @param int|string|null $id
     * @param Request         $request
     *
     * @return Response
     *
     * @throws NotFoundHttpException If the object does not exist
     * @throws AccessDeniedException If access is not granted
     */
    public function createInvoiceAction($id = null, Request $request)
    {
        $request = $this->resolveRequest($request);
        $id = $request->get($this->admin->getIdParameter());

        /** @var Receipt $object */
        $object = $this->admin->getObject($id);

        if (!$object) {
            throw $this->createNotFoundException(sprintf('unable to find the object with id : %s', $id));
        }

        $invoice = new Invoice();
        $invoice
            ->setReceipt($object)
            ->setStudent($object->getStudent())
            ->setPerson($object->getPerson())
            ->setDate($object->getDate())
            ->setIsPayed($object->getIsPayed() ? $object->getIsPayed() : false)
            ->setPaymentDate($object->getPaymentDate() ? $object->getPaymentDate() : null)
            ->setBaseAmount($object->getBaseAmount())
            ->setDiscountApplied($object->isDiscountApplied())
            ->setMonth($object->getMonth())
            ->setYear($object->getYear())
            ->setIsSended(false)
        ;
        foreach ($object->getLines() as $line) {
            $invoiceLine = new InvoiceLine();
            $invoiceLine
                ->setInvoice($invoice)
                ->setDescription($line->getDescription())
                ->setUnits($line->getUnits())
                ->setPriceUnit($line->getPriceUnit())
                ->setDiscount($line->getDiscount())
                ->setTotal($line->getTotal())
            ;
            $invoice->addLine($invoiceLine);
        }
        // TODO set IRPF, IVA, etc..

        $em = $this->container->get('doctrine')->getManager();
        $em->persist($invoice);
        $em->flush();

        /* @var Controller $this */
        $this->addFlash('success', 'S\'ha generat la factura núm. '.$invoice->getInvoiceNumber());

        return $this->redirectToList();
    }

    /**
     * Generate PDF receipt action.
     *
     * @param int|string|null $id
     * @param Request         $request
     *
     * @return Response
     *
     * @throws NotFoundHttpException If the object does not exist
     * @throws AccessDeniedException If access is not granted
     */
    public function pdfAction($id = null, Request $request)
    {
        $request = $this->resolveRequest($request);
        $id = $request->get($this->admin->getIdParameter());

        /** @var Receipt $object */
        $object = $this->admin->getObject($id);

        if (!$object) {
            throw $this->createNotFoundException(sprintf('unable to find the object with id : %s', $id));
        }

        /** @var ReceiptPdfBuilderService $rps */
        $rps = $this->get('app.receipt_pdf_builder');
        $pdf = $rps->build($object);

        return new Response($pdf->Output('box_idiomes_receipt_'.$object->getSluggedReceiptNumber().'.pdf', 'I'), 200, array('Content-type' => 'application/pdf'));
    }

    /**
     * Send PDF receipt action.
     *
     * @param int|string|null $id
     * @param Request         $request
     *
     * @return Response
     *
     * @throws NotFoundHttpException If the object does not exist
     * @throws AccessDeniedException If access is not granted
     */
    public function sendAction($id = null, Request $request)
    {
        $request = $this->resolveRequest($request);
        $id = $request->get($this->admin->getIdParameter());

        /** @var Receipt $object */
        $object = $this->admin->getObject($id);

        if (!$object) {
            throw $this->createNotFoundException(sprintf('unable to find the object with id : %s', $id));
        }

        /* @var Controller $this */
        $this->addFlash('danger', 'Aquesta funcionalitat encara no està disponible. No s\'ha enviat cap rebut per email.');

        return $this->redirectToList();
    }
}
