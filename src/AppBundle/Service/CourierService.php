<?php

namespace AppBundle\Service;

/**
 * Class CourierService
 *
 * @category Service
 * @package  AppBundle\Service
 * @author   David Romaní <david@flux.cat>
 */
class CourierService
{
    /**
     * @var \Swift_Mailer
     */
    private $mailer;

    /**
     *
     *
     * Methods
     *
     *
     */

    /**
     * CourierService constructor
     *
     * @param \Swift_Mailer $mailer
     */
    public function __construct(\Swift_Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

    /**
     * Send an email
     *
     * @param string $from
     * @param string $to
     * @param string $subject
     * @param string $body
     * @param string|null $replyAddress
     *
     * @return int
     */
    public function sendEmail($from, $to, $subject, $body, $replyAddress = null)
    {
        $message = new \Swift_Message();
        $message
            ->setSubject($subject)
            ->setFrom($from)
            ->setTo($to)
            ->setBody($body)
            ->setCharset('UTF-8')
            ->setContentType('text/html');
        if (!is_null($replyAddress)) {
            $message->setReplyTo($replyAddress);
        }

        return $this->mailer->send($message);
    }
}
