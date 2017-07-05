<?php

namespace AppBundle\Manager;

use AppBundle\Entity\NewsletterContact;
use AppBundle\Service\NotificationService;
use \DrewM\MailChimp\MailChimp;

/**
 * Class MailchimpManager
 *
 * @category Manager
 * @package  AppBundle\Manager
 * @author   Anton Serra <aserratorta@gmail.com>
 */
class MailchimpManager
{
    const SUBSCRIBED = 'subscribed';

    /**
     * @var MailChimp $mailChimp
     */
     private $mailChimp;

    /**
     * @var NotificationService
     */
    private $messenger;

    /**
     *
     *
     * Methods
     *
     *
     */

    /**
     * MailchimpManager constructor.
     *
     * @param NotificationService $messenger
     * @param string              $apiKey
     */
    public function __construct(NotificationService $messenger, $apiKey)
    {
        $this->mailChimp = new MailChimp($apiKey);
        $this->messenger = $messenger;
    }

    /**
     * Mailchimp Manager
     *
     * @param NewsletterContact $newsletterContact
     * @param string            $listId
     *
     * @return bool $result = false if everything goes well
     */
    public function subscribeContactToList(NewsletterContact $newsletterContact, $listId)
    {
        // make HTTP API request
        $result = $this->mailChimp->post('lists/' . $listId . '/members', array(
            'email_address' => $newsletterContact->getEmail(),
            'status'        => self::SUBSCRIBED,
        ));

        // check error
        if (is_array($result) && $result['status'] == self::SUBSCRIBED) {
            $this->messenger->sendCommonNewsletterUserNotification($newsletterContact);
        } else {
            $this->messenger->sendFailureNewsletterSubscriptionAdminNotification($newsletterContact);
        }

        return $result;
    }
}
