<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\Teacher;
use Symfony\Component\HttpFoundation\File\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class TeacherAdminController.
 *
 * @category Controller
 */
class TeacherAdminController extends BaseAdminController
{
    /**
     * Detail action.
     *
     * @param Request $request
     *
     * @return Response
     *
     * @throws NotFoundHttpException If the object does not exist
     * @throws AccessDeniedException If access is not granted
     */
    public function detailAction(Request $request = null)
    {
        $request = $this->resolveRequest($request);
        $id = $request->get($this->admin->getIdParameter());

        /** @var Teacher $object */
        $object = $this->admin->getObject($id);

        if (!$object) {
            throw $this->createNotFoundException(sprintf('unable to find the object with id : %s', $id));
        }

        $absences = $this->container->get('app.teacher_absence_repository')->getTeacherAbsencesSortedByDate($object);

        return $this->renderWithExtraParams(
            '::Admin/Teacher/detail.html.twig',
            array(
                'action' => 'show',
                'object' => $object,
                'absences' => $absences,
                'elements' => $this->admin->getShow(),
            )
        );
    }
}
