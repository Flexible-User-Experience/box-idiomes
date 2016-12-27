<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="app_homepage")
     */
    public function indexAction(Request $request)
    {
        return $this->render('Front/homepage.html.twig', array(
            'base_dir' => realpath($this->container->getParameter('kernel.root_dir').'/..').DIRECTORY_SEPARATOR,
        ));
    }

    /**
     * @Route("/professors", name="app_teachers")
     */
    public function teachersAction()
    {
        return $this->render(
            'Front/teachers.html.twig'
//            ['teachers' => $teachers]
        );
    }

    /**
     * @Route("/serveis", name="app_services")
     */
    public function servicesAction()
    {
        return $this->render(
            'Front/services.html.twig'
//            ['teachers' => $teachers]
        );
    }

    /**
     * @Route("/contacte", name="app_contact")
     */
    public function contactAction()
    {
        return $this->render(
            'Front/contact.html.twig'
//            ['teachers' => $teachers]
        );
    }
}
