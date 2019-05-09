<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/{_locale}")
 */
class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function home()
    {
        return $this->render('home/index.html.twig', [

        ]);
    }

    /**
     * @Route("/activities", name="activities")
     */
    public function activities()
    {
        return $this->render('home/activities.html.twig', [

        ]);
    }

    /**
     * @Route("/mentions", name="mentions")
     */
    public function mentions()
    {
        return $this->render('home/mentions.html.twig', [

        ]);
    }

    /**
     * @Route("/partners", name="partners")
     */
    public function partners()
    {
        return $this->render('home/partners.html.twig', [

        ]);
    }

    /**
     * @Route("/courses", name="courses")
     */
    public function courses()
    {
        return $this->render('home/courses.html.twig', [

        ]);
    }

    /**
     * @Route("/sitemap", name="sitemap")
     */
    public function sitemap()
    {
        return $this->render('home/sitemap.html.twig', [

        ]);
    }
}
