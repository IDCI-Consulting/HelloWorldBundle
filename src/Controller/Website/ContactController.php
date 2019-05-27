<?php

namespace App\Controller\Website;

use App\Form\Type\ContactType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/{_locale}")
 */
class ContactController extends AbstractController
{
    /**
     * @Route("/contact", name="contact", methods={"GET","POST"})
     */
    public function contact(Request $request, \Swift_Mailer $mailer)
    {
        $form = $this->createForm(ContactType::class);
        $message = '';

        if ($request->isXmlHttpRequest()) {
            $response = new Response();

            $response->setContent($this->renderView('partials/contactForm.html.twig', [
                'form' => $form->createView()
            ]));

            return $response;
        } elseif ($request->getMethod() == "POST") {
            $form->handleRequest($request);

            if ($form->isValid()) {
                $data = $form->getData();

                $this->addFlash('success', 'Your email has been send !');

                $message = (new \Swift_Message('Hello Email'))
                    ->setSubject('Nouvelle demande de projet')
                    ->setFrom('no-reply@idci-consulting.fr')
                    ->setTo(array('contact@idci-consulting.fr'))
                    ->setBody(
                        $this->renderView(
                            'partials/email.html.twig',
                            [
                                'company'       => $data['company'],
                                'name'          => $data['name'],
                                'firstName'     => $data['firstname'],
                                'project'       => $data['project'],
                                'phoneNumber'   => $data['phonenumber'],
                                'email'         => $data['email'],
                            ]
                        ),
                        'text/html'
                    );

                $mailer->send($message);

                $alert = true;
            } else {
                $this->addFlash('error', 'Your message could not be send');
            }
        }

        return $this->render('contact/contact.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
