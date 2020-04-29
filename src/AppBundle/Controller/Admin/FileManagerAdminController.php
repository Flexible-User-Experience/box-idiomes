<?php

namespace AppBundle\Controller\Admin;

use Symfony\Component\HttpFoundation\File\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class FileManagerAdminController.
 *
 * @category Controller
 */
class FileManagerAdminController extends BaseAdminController
{
    /**
     * Show File manager view.
     *
     * @return Response
     *
     * @throws NotFoundHttpException If the object does not exist
     * @throws AccessDeniedException If access is not granted
     */
    public function handlerAction()
    {
        return $this->renderWithExtraParams(
            '::Admin/Invoice/generate_invoice_form.html.twig',
            array(
                '_sonata_admin' => 'admin.file_manager',
                'action' => 'generate',
                'year_month_form' => 1980,
                'form' => [],
                'generate_invoice' => [],
            )
        );
    }
}
