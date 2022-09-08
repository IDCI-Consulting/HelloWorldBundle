<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/{_locale}", requirements={"_locale": "fr|en"})
 */
class PageController extends AbstractController
{
    /**
     * @Route("/", methods={"GET"}, name="homepage")
     */
    public function homepage(Request $request): Response
    {
        return $this->render('page/homepage.html.twig');
    }

    /**
     * @Route("/legal-mentions", methods={"GET"}, name="legal_mentions")
     */
    public function legalMentions(Request $request): Response
    {
        return $this->render('page/legal_mentions.html.twig');
    }

    /**
     * @Route("/offers", methods={"GET"}, name="offers")
     */
    public function offers(Request $request): Response
    {
        return $this->render('page/offers.html.twig');
    }
}