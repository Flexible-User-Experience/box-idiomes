<?php

namespace AppBundle\Service;

/**
 * Class CourierService.
 *
 * @category Service
 */
class CourierService
{
    /**
     * @var \Swift_Mailer
     */
    private $mailer;

    /**
     * Methods.
     */

    /**
     * CourierService constructor.
     *
     * @param \Swift_Mailer $mailer
     */
    public function __construct(\Swift_Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

    /**
     * Build an email.
     *
     * @param string      $from
     * @param string      $toEmail
     * @param string      $subject
     * @param string      $body
     * @param string|null $replyAddress
     * @param string|null $toName
     *
     * @return \Swift_Message
     */
    private function buildSwiftMesage($from, $toEmail, $subject, $body, $replyAddress = null, $toName = null)
    {
        $message = new \Swift_Message();
        $message
            ->setSubject($subject)
            ->setFrom($from)
            ->setTo($toEmail, $toName)
            ->setBody($body)
            ->setCharset('UTF-8')
            ->setContentType('text/html');
        if (!is_null($replyAddress)) {
            $message->setReplyTo($replyAddress);
        }

        return $message;
    }

    /**
     * Send an email.
     *
     * @param string      $from
     * @param string      $toEmail
     * @param string      $subject
     * @param string      $body
     * @param string|null $replyAddress
     * @param string|null $toName
     *
     * @return int
     */
    public function sendEmail($from, $toEmail, $subject, $body, $replyAddress = null, $toName = null)
    {
        $message = $this->buildSwiftMesage($from, $toEmail, $subject, $body, $replyAddress, $toName);

        return $this->mailer->send($message);
    }

    /**
     * Send an email with an attatchment PDF.
     *
     * @param string $from
     * @param string $toEmail
     * @param string $toName
     * @param string $subject
     * @param string $body
     * @param \TCPDF $pdf
     *
     * @return int
     */
    public function sendEmailWithPdfAttached($from, $toEmail, $toName, $subject, $body, \TCPDF $pdf)
    {
        $message = $this->buildSwiftMesage($from, $toEmail, $subject, $body, null, $toName);

        return $this->mailer->send($message);
    }
}
