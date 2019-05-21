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
     * @Route("/contact", name="contact", methods={"GET"})
     */
    public function contact(Request $request)
    {
        $form = $this->createForm(ContactType::class);

        if ($request->isXmlHttpRequest()) {
            $response = new Response();

            $response->setContent($this->renderView('partials/contactForm.html.twig', [
                'form' => $form->createView()
            ]));

            return $response;
        }
        
        return $this->render('contact/contact.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
