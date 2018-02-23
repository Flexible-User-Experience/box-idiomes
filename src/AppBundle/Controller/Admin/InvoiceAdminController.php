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
     * @throws NotFoundHttpException If the object does not exist
     * @throws AccessDeniedException If access is not granted
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function generateAction(Request $request = null)
    {
//        $object = new Invoice();
        $form = $this->createForm(GenerateInvoiceType::class);
        $form->handleRequest($request);

        if (!$form->isSubmitted()) {
            $form->remove('generate');
        }

        $students = [];
        if ($form->isSubmitted() && $form->isValid()) {
            $year = $form->getData()['year'];
            $month = $form->getData()['month'];
            $students = $this->get('app.student_repository')->getStudentsInEventsByYearAndMonth($year, $month);
            if ($form->get('preview')->isClicked()) {
            }

            if ($form->get('generate')->isClicked()) {
                /** @var EntityManager $em */
                $em = $this->get('doctrine')->getManager();
                /** @var Student $student */
                foreach ($students as $student) {
                    $invoiceLine = new InvoiceLine();
                    $invoiceLine
                        ->setDescription('Classes d\'anglès mensual')
                        ->setUnits(1)
                        ->setPriceUnit($student->calculateMonthlyTariff())
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
                        ->setDiscountApplied($student->calculateMonthlyDiscount() ? true : false)
                        ->addLine($invoiceLine)
                        ->setTaxParcentage($invoice->calculateTaxParcentage())
                        ->setIrpf($invoice->calculateIrpf())
                        ->setTotalAmount($invoice->calculateTotal())
                    ;
                    $em->persist($invoice);
                }

                $em->flush();

                $this->addFlash('success', 'S\'han generat '.count($students).' factures correctament.');

                return $this->redirectToList('admin_app_invoice_list');
            }
        }

        return $this->render(
            '::Admin/Invoice/generate_invoice_form.html.twig',
            array(
                'action'   => 'generate',
 //               'object'   => $object,
                'form'     => $form->createView(),
                'students' => $students,
            ),
            null,
            $request
        );
    }
}
