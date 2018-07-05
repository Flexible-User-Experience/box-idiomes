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
     * @param string      $to
     * @param string      $subject
     * @param string      $body
     * @param string|null $replyAddress
     *
     * @return \Swift_Message
     */
    private function buildSwiftMesage($from, $to, $subject, $body, $replyAddress = null)
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

        return $message;
    }

    /**
     * Send an email.
     *
     * @param string      $from
     * @param string      $to
     * @param string      $subject
     * @param string      $body
     * @param string|null $replyAddress
     *
     * @return int
     */
    public function sendEmail($from, $to, $subject, $body, $replyAddress = null)
    {
        $message = $this->buildSwiftMesage($from, $to, $subject, $body, $replyAddress);

        return $this->mailer->send($message);
    }

    /**
     * Send an email with an attatchment PDF.
     *
     * @param string      $from
     * @param string      $to
     * @param string      $subject
     * @param string      $body
     * @param string|null $replyAddress
     * @param \TCPDF      $pdf
     *
     * @return int
     */
    public function sendEmailWithPdfAttached($from, $to, $subject, $body, $replyAddress = null, \TCPDF $pdf)
    {
        $message = $this->buildSwiftMesage($from, $to, $subject, $body, $replyAddress);

        return $this->mailer->send($message);
    }
}
