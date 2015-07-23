<?php

namespace Manager;

/**
 * ContactManager
 *
 * @author Brahim Boukoufallah <brahim.boukoufallah@idci-consulting.fr>
 *
 */
class ContactManager
{
    /**
     * @var \Twig_Environment $twig
     */
    private $twig;

    /**
     * @var \Swift_Mailer $mailer
     */
    private $mailer;

    /**
     * Constructor.
     *
     * @param \Twig_Environment $twig
     * @param \Swift_Mailer $mailer
     */
    public function __construct(\Twig_Environment $twig, \Swift_Mailer $mailer)
    {
        $this->twig   = $twig;
        $this->mailer = $mailer;
    }

    /**
     * Send a mail
     *
     * @param $data
     */
    public function sendMail($data)
    {
        $message = \Swift_Message::newInstance()
            ->setSubject('Nouvelle demande de projet')
            ->setFrom('no-reply@idci-consulting.fr')
            ->setTo(array('contact@idci-consulting.fr'))
            ->setBody(
                $this->twig->render(
                    'pages/email.html.twig',
                    array(
                        'company'       => $data['company'],
                        'name'          => $data['name'],
                        'firstName'     => $data['firstname'],
                        'project'       => $data['project'],
                        'phoneNumber'   => $data['phonenumber'],
                        'email'         => $data['email'],
                    )
                ),
                'text/html'
            )
        ;

        $this->mailer->send($message);
    }
}
