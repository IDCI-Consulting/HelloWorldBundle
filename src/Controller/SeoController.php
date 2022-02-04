<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/seo", name="seo_")
 */
class SeoController extends AbstractController
{
    /**
     * @Route("/sitemap", methods={"GET"}, name="sitemap")
     */
    public function sitemap(Request $request): Response
    {
        return $this->render('seo/sitemap.html.twig');
    }

    /**
     * @Route("/robots.txt", methods={"GET"}, name="robots.txt")
     */
    public function robotsTxt(Request $request, String $slug): Response
    {
        return $this->render('seo/robots_txt.html.twig');
    }
}