<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mime\Email;

/**
 * @Route("/mail", name="mail_")
 */
class MailTestController extends AbstractController
{
    private MailerInterface $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    /**
     * @Route("/", methods={"GET"}, name="index")
     */
    public function index(Request $request): Response
    {
        $email = (new Email())
            ->from('joris.viniere@idci-consulting.fr')
            ->to('joris.viniere@idci-consulting.fr')
            ->subject('Test')
            ->text('Test')
            ->html('<p>Test</p>')
        ;

        $this->mailer->send($email);

        return $this->render('/mail/index.html.twig');
    }
}