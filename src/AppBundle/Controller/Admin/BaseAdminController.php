<?php

namespace AppBundle\Controller\Admin;

use Sonata\AdminBundle\Controller\CRUDController as Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class BaseAdminController.
 *
 * @category Controller
 */
abstract class BaseAdminController extends Controller
{
    /**
     * @param Request|null $request
     *
     * @return Request
     */
    protected function resolveRequest(Request $request = null)
    {
        if (null === $request) {
            return $this->getRequest();
        }

        return $request;
    }
}
