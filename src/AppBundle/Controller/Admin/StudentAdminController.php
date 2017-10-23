<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\Student;
use Symfony\Component\HttpFoundation\File\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class StudentAdminController.
 *
 * @category Controller
 */
class StudentAdminController extends BaseAdminController
{
    /**
     * Image rights pdf action.
     *
     * @param int|string|null $id
     * @param Request         $request
     *
     * @return Response
     *
     * @throws NotFoundHttpException If the object does not exist
     * @throws AccessDeniedException If access is not granted
     */
    public function imagerightsAction($id = null, Request $request = null)
    {
        $request = $this->resolveRequest($request);
        $id = $request->get($this->admin->getIdParameter());

        /** @var Student $object */
        $object = $this->admin->getObject($id);

        if (!$object) {
            throw $this->createNotFoundException(sprintf('unable to find the object with id : %s', $id));
        }

        $sirps = $this->get('app.student_image_rights_pdf_builder');
    }
}
