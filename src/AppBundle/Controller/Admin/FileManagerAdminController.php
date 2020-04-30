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
            '::Admin/FileManager/show_file_manager.html.twig',
            array(
                '_sonata_admin' => 'admin.file_manager_dummy',
                'action' => 'show',
            )
        );
    }
}
