<?php

namespace App\Controller\Website;

use App\Generator\AsideMenuGenerator;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/{_locale}")
 */
class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home", methods={"GET"})
     */
    public function home(AsideMenuGenerator $asideMenuGenerator)
    {
        return $this->render('home/index.html.twig', [
            'aside_menu' => $asideMenuGenerator->generateAsideMenu('home/index.html.twig')
        ]);
    }

    /**
     * @Route("/references", name="references", methods={"GET"})
     */
    public function references()
    {
        return $this->render('home/references.html.twig');
    }

    /**
     * @Route("/offers", name="offers", methods={"GET"})
     */
    public function offers()
    {
        return $this->render('home/offers.html.twig');
    }

    /**
     * @Route("/legal_mentions", name="legal_mentions", methods={"GET"})
     */
    public function mentions()
    {
        return $this->render('home/mentions.html.twig');
    }

    /**
     * @Route("/sitemap", name="sitemap", methods={"GET"})
     */
    public function sitemap()
    {
        return $this->render('home/sitemap.html.twig');
    }
}
