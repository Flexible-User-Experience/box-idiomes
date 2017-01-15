<?php

namespace AppBundle\Controller;

use AppBundle\Entity\ContactMessage;
use AppBundle\Form\Type\ContactHomepageType;
use AppBundle\Form\Type\ContactMessageType;
use AppBundle\Service\NotificationService;
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
        $teachers = $this->getDoctrine()->getRepository('AppBundle:Teacher')->findAllEnabledSortedByPosition();

        $contact = new ContactMessage();
        $newsletterForm = $this->createForm(ContactHomepageType::class, $contact);
        $newsletterForm->handleRequest($request);

        if ($newsletterForm->isSubmitted() && $newsletterForm->isValid()) {
            $this->setFlashMessageAndEmailNotifications($contact);
            // Clean up new form
            $newsletterForm = $this->createForm(ContactHomepageType::class);
        }

        return $this->render('Front/homepage.html.twig',
            [
                'teachers'       => $teachers,
                'newsletterForm' => $newsletterForm->createView(),
            ]
        );
    }

    /**
     * @param ContactMessage $contact
     */
    private function setFlashMessageAndEmailNotifications($contact)
    {
        /** @var NotificationService $messenger */
        $messenger = $this->get('app.notification');
            // Send email notifications
        $messenger->sendCommonUserNotification($contact);
        $messenger->sendNewsletterSubscriptionAdminNotification($contact);
        // Set frontend flash message
        $this->addFlash(
            'notice',
            'El teu missatge s\'ha enviat correctament'
        );
    }

    /**
     * @Route("/serveis", name="app_services")
     *
     * @return Response
     */
    public function servicesAction()
    {
        $services = $this->getDoctrine()->getRepository('AppBundle:Service')
            ->findAllEnabledSortedByPosition();

        return $this->render(
            'Front/services.html.twig',
            ['services' => $services]
        );
    }

    /**
     * @Route("/quisom", name="app_aboutus")
     *
     * @return Response
     */
    public function aboutusAction()
    {
        return $this->render('Front/about_us.html.twig');
    }

    /**
     * @Route("/contacte", name="app_contact")
     *
     * @param Request $request
     *
     * @return Response
     */
    public function contactAction(Request $request)
    {
        $contactMessage = new ContactMessage();
        $contactMessageForm = $this->createForm(ContactMessageType::class, $contactMessage);
        $contactMessageForm->handleRequest($request);

        if ($contactMessageForm->isSubmitted() && $contactMessageForm->isValid()) {
            // Set frontend flash message
            $this->addFlash(
                'notice',
                'El teu missatge s\'ha enviat correctament'
            );
            // Persist new contact message into DB
            $em = $this->getDoctrine()->getManager();
            $em->persist($contactMessage);
            $em->flush();
            // Send email notifications
            /** @var NotificationService $messenger */
            $messenger = $this->get('app.notification');
            $messenger->sendCommonUserNotification($contactMessage);
            $messenger->sendContactAdminNotification($contactMessage);
            // Clean up new form
            $contactMessageForm = $this->createForm(ContactHomepageType::class);
        }

        return $this->render(
            'Front/contact.html.twig',
            ['contactMessageForm' => $contactMessageForm->createView()]
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
