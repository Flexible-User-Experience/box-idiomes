<?php

namespace AppBundle\Controller\Admin;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\File\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class FileManagerAdminController.
 *
 * @category Controller
 */
class FileManagerAdminController extends BaseAdminController
{
//    public $admin = 'admin.file_manager';

    /**
     * Show File manager view.
     *
     * @Route("/admin/files-manager/show", name="file_manager_sonata")
     * @Method("GET")
     *
     * @return Response
     *
     * @throws NotFoundHttpException If the object does not exist
     * @throws AccessDeniedException If access is not granted
     */
    public function showFileManagerAction()
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
