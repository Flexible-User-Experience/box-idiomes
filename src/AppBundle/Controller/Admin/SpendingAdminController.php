<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\Spending;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class SpendingAdminController.
 *
 * @category Controller
 */
class SpendingAdminController extends BaseAdminController
{
    /**
     * Duplicate a spending record action.
     *
     * @param Request $request
     *
     * @return Response
     *
     * @throws NotFoundHttpException If the object does not exist
     * @throws \Exception
     */
    public function duplicateAction(Request $request)
    {
        $request = $this->resolveRequest($request);
        $id = $request->get($this->admin->getIdParameter());

        /** @var Spending $object */
        $object = $this->admin->getObject($id);
        if (!$object) {
            throw $this->createNotFoundException(sprintf('unable to find the object with id: %s', $id));
        }

        // new spending
        $newSpending = new Spending();
        $newSpending
            ->setDate(new \DateTime())
            ->setCategory($object->getCategory())
            ->setProvider($object->getProvider())
            ->setDescription($object->getDescription())
            ->setBaseAmount($object->getBaseAmount())
            ->setIsPayed(false)
            ->setPaymentMethod($object->getPaymentMethod())
        ;

        $em = $this->container->get('doctrine')->getManager();
        $em->persist($newSpending);
        $em->flush();

        $this->addFlash('success', 'S\'ha duplicat la despesa núm. '.$object->getId().' amb la factura núm. '.$newSpending->getId().' correctament.');

        return $this->redirectToList();
    }
}
