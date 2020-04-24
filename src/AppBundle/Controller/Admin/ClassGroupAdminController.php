<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\ClassGroup;
use AppBundle\Pdf\InvoiceBuilderPdf;
use Symfony\Bundle\FrameworkBundle\Translation\Translator;
use Symfony\Component\HttpFoundation\File\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class ClassGroupAdminController.
 *
 * @category Controller
 */
class ClassGroupAdminController extends BaseAdminController
{
    /**
     * Get group emails list in PDF action.
     *
     * @param Request $request
     *
     * @return Response
     *
     * @throws NotFoundHttpException If the object does not exist
     * @throws AccessDeniedException If access is not granted
     */
    public function emailsAction(Request $request)
    {
        /** @var Translator $translator */
        $translator = $this->container->get('translator.default');
        $request = $this->resolveRequest($request);
        $id = $request->get($this->admin->getIdParameter());

        /** @var ClassGroup $object */
        $object = $this->admin->getObject($id);
        if (!$object) {
//            throw $this->createNotFoundException(sprintf('unable to find the object with id: %s', $id));
            $this->addFlash('warning', $translator->trans('backend.admin.invoice.generator.no_records_presisted'));
        } else {
            $this->addFlash('success', $translator->trans('backend.admin.invoice.generator.flash_success', array('%amount%' => $object->getId()), 'messages'));
        }

        /* @var InvoiceBuilderPdf $ips */
//        $ips = $this->container->get('app.invoice_pdf_builder');
//        $pdf = $ips->build($object);

        return $this->redirectToList();
    }
}
