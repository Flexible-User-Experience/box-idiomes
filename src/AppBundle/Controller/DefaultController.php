<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="app_homepage")
     *
     * @return Response
     */
    public function indexAction(Request $request)
    {
        return $this->render('Front/homepage.html.twig');
    }

    /**
     * @Route("/professors", name="app_teachers")
     *
     * @return Response
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
     *
     * @return Response
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
     *
     * @return Response
     */
    public function contactAction()
    {
        return $this->render(
            'Front/contact.html.twig'
//            ['teachers' => $teachers]
        );
    }

    /**
     * @Route("/politica-de-privacitat", name="app_privacy_policy")
     *
     * @return Response
     */
    public function privacyPolicyAction()
    {
        return $this->render('Front/privacy_policy.html.twig', array());
    }

    /**
     * @Route("/credits", name="app_credits")
     *
     * @return Response
     */
    public function creditsAction()
    {
        return $this->render('Front/credits.html.twig');
    }
}
