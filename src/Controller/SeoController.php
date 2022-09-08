<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/", name="seo_")
 */
class SeoController extends AbstractController
{
    /**
     * @Route("/sitemap.xml", methods={"GET"}, name="sitemap")
     */
    public function sitemap(Request $request): Response
    {
        $response = new Response($this->renderView('seo/sitemap.xml.twig'));
        $response->headers->set('Content-Type', 'text/xml');

        return $response;
    }

    /**
     * @Route("/robots.txt", methods={"GET"}, name="robots")
     */
    public function robotsTxt(Request $request): Response
    {
        $response = $this->render('seo/robots_txt.html.twig');
        $response->headers->set('Content-Type', 'text/plain');

        return $response;
    }
}