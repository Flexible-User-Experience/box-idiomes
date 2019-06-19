<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\StudentAbsence;
use Symfony\Component\HttpFoundation\File\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class StudentAbsenceAdminController.
 *
 * @category Controller
 */
class StudentAbsenceAdminController extends BaseAdminController
{
    /**
     * Show action.
     *
     * @param int|string|null $id
     *
     * @return RedirectResponse
     *
     * @throws NotFoundHttpException If the object does not exist
     * @throws AccessDeniedException If access is not granted
     * @throws \Exception
     */
    public function notificationAction($id = null)
    {
        $request = $this->getRequest();
        $id = $request->get($this->admin->getIdParameter());

        /** @var StudentAbsence $object */
        $object = $this->admin->getObject($id);
        if (!$object) {
            throw $this->createNotFoundException(sprintf('unable to find the object with id: %s', $id));
        }

        $this->admin->checkAccess('show', $object);
        $object
            ->setHasBeenNotified(true)
            ->setNotificationDate(new \DateTime())
        ;

        $em = $this->container->get('doctrine')->getManager();
        $em->flush();

        $messenger = $this->container->get('app.notification');
        $messenger->sendStudentAbsenceNotification($object);

        $this->addFlash('success', 'S\'ha enviat un notificació per correu electrònic a l\'adreça '.$object->getStudent()->getMainEmailSubject().' advertint que l\'alumne '.$object->getStudent()->getFullName().' no ha assistit a la classe del dia '.$object->getDayString().'.');

        return $this->redirectToList();
    }
}
