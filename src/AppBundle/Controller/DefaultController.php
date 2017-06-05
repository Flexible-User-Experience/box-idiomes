<?php

namespace AppBundle\Controller;

use AppBundle\Entity\ContactMessage;
use AppBundle\Entity\NewsletterContact;
use AppBundle\Form\Type\ContactHomepageType;
use AppBundle\Form\Type\ContactMessageType;
use AppBundle\Manager\MailchimpManager;
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
     * @param Request $request
     *
     * @return Response
     */
    public function indexAction(Request $request)
    {
        $teachers = $this->getDoctrine()->getRepository('AppBundle:Teacher')->findAllEnabledSortedByPosition();

        $contact = new NewsletterContact();
        $newsletterForm = $this->createForm(ContactHomepageType::class, $contact);
        $newsletterForm->handleRequest($request);

        if ($newsletterForm->isSubmitted() && $newsletterForm->isValid()) {
            // Persist new contact message into DB
            $em = $this->getDoctrine()->getManager();
            $em->persist($contact);
            $em->flush();
            /** @var MailchimpManager $mailchimpManager */
            $mailchimpManager = $this->get('app.mailchimp_manager');

            $this->setFlashMessageAndEmailNotifications($contact);
            // Subscribe contact to mailchimp list
            $mailchimpManager->subscribeContactToList($contact, $this->getParameter('mailchimp_test_list_id'));
            // Clean up new form
            $contact = new NewsletterContact();
            $newsletterForm = $this->createForm(ContactHomepageType::class, $contact);
        }

        return $this->render('Front/homepage.html.twig',
            [
                'teachers'       => $teachers,
                'newsletterForm' => $newsletterForm->createView(),
            ]
        );
    }

    /**
     * @param NewsletterContact $newsletterContact
     */
    private function setFlashMessageAndEmailNotifications($newsletterContact)
    {
        /** @var NotificationService $messenger */
        $messenger = $this->get('app.notification');
            // Send email notifications
        if ($messenger->sendCommonNewsletterUserNotification($newsletterContact) != 0) {
            // Set frontend flash message
            $this->addFlash(
                'notice',
                'El teu missatge s\'ha enviat correctament'
            );
        } else {
            $this->addFlash(
                'danger',
                'El teu missatge no s\'ha enviat'
            );
        }
        $messenger->sendNewsletterSubscriptionAdminNotification($newsletterContact);
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
     * @Route("/academia", name="app_academy")
     *
     * @return Response
     */
    public function academyAction()
    {
        return $this->render('Front/academy.html.twig');
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
            // Persist new contact message into DB
            $em = $this->getDoctrine()->getManager();
            $em->persist($contactMessage);
            $em->flush();
            // Send email notifications
            /** @var NotificationService $messenger */
            $messenger = $this->get('app.notification');
            if ($messenger->sendCommonUserNotification($contactMessage) != 0) {
                // Set frontend flash message
                $this->addFlash(
                    'notice',
                    'El teu missatge s\'ha enviat correctament'
                );
            } else {
                $this->addFlash(
                    'danger',
                    'El teu missatge no s\'ha enviat'
                );
            }
            $messenger->sendContactAdminNotification($contactMessage);
            // Clean up new form
            $contactMessage = new ContactMessage();
            $contactMessageForm = $this->createForm(ContactMessageType::class, $contactMessage);
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

    /**
     * @Route("/test-email", name="app_test_email")
     *
     * @return Response
     */
    public function testEmailAction()
    {
        $contact = new ContactMessage();
        $contact->setName('Anton');
//        $contact->setEmail('Anton@gmail.com');
//        $contact = $this->getDoctrine()->getRepository('AppBundle:ContactMessage')->find(1);

        return$this->render(':Mails:base.html.twig', array(
            'contact'=> $contact
        ));
    }
}
