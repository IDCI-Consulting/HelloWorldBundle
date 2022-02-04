<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class HomeController extends AbstractController
{
    /**
     * @Route("/", methods={"GET"}, name="homepage")
     */
    public function homepage(Request $request): Response
    {
        return $this->render('home/homepage.html.twig');
    }

    /**
     * @Route("/legal-mentions", methods={"GET"}, name="legal-mentions")
     */
    public function legalMentions(Request $request): Response
    {
        return $this->render('home/legal_mentions.html.twig');
    }

    /**
     * @Route("/partners", methods={"GET"}, name="partners")
     */
    public function partners(Request $request): Response
    {
        return $this->render('home/partners.html.twig');
    }

    /**
     * @Route("/activities", methods={"GET"}, name="activities")
     */
    public function activities(Request $request): Response
    {
        return $this->render('home/activities.html.twig');
    }
}