<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\InvoiceLine;
use AppBundle\Entity\Invoice;
use AppBundle\Entity\Student;
use AppBundle\Form\Type\GenerateInvoiceType;
use Doctrine\ORM\EntityManager;
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
     * @throws NotFoundHttpException                 If the object does not exist
     * @throws AccessDeniedException                 If access is not granted
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function generateAction(Request $request = null)
    {
        $form = $this->createForm(GenerateInvoiceType::class);
        $form->handleRequest($request);

        $students = [];
        $hideGenerateSubmitButton = false;

        if (!$form->isSubmitted()) {
            $form->remove('generate');
            $hideGenerateSubmitButton = true;
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $year = $form->getData()['year'];
            $month = $form->getData()['month'];
            $students = $this->get('app.student_repository')->getStudentsInEventsByYearAndMonthSortedBySurname($year, $month);
            // preview invoices action
            if ($form->get('preview')->isClicked()) {
                if (0 == count($students)) {
                    $hideGenerateSubmitButton = true;
                }
            }
            // generate invoices action
            if ($form->get('generate')->isClicked()) {
                $translator = $this->container->get('translator.default');
                /** @var EntityManager $em */
                $em = $this->get('doctrine')->getManager();
                /** @var Student $student */
                foreach ($students as $student) {
                    $invoiceLine = new InvoiceLine();
                    $invoiceLine
                        ->setStudent($student)
                        ->setDescription($translator->trans('backend.admin.invoiceLine.generator.line', array('%month%' => $month, '%year%' => $year), 'messages'))
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
                        ->setTotalAmount($invoice->calculateTotal())
                    ;
                    $em->persist($invoice);
                }
                $em->flush();
                $this->addFlash('success', $translator->trans('backend.admin.invoice.generator.flash_success', array('%amount%' => count($students)), 'messages'));

                return $this->redirectToList('admin_app_invoice_list');
            }
        }

        return $this->render(
            '::Admin/Invoice/generate_invoice_form.html.twig',
            array(
                'action' => 'generate',
                'form' => $form->createView(),
                'students' => $students,
                'hide_generate_button' => $hideGenerateSubmitButton,
            ),
            null,
            $request
        );
    }
}
