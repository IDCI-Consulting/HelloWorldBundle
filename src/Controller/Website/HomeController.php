<?php

namespace App\Controller\Website;

use App\Form\Type\ContactType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/{_locale}")
 * 
 */
class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home", methods={"GET"})
     */
    public function home()
    {
        $page = $this->render('home/index.html.twig');
        $asideMenu = [];

        // Get all sections in the DOM with an id
        preg_match_all('/(?<section><section[ ]*id.*<\/section>)/siU', $page, $matched_sections);

        /*
         * Retrieve the ids and the text (inside h2 tag which is child of header)
         */
        foreach ($matched_sections['section'] as $i => $section) {
            preg_match_all('/<section[ ]*id="(?<id>.+)".*<h2.*>(?<title>.*)</siU', $section, $matches);
            foreach ($matches['id'] as $j => $id) {
                $asideMenu[$id] = $matches['title'][$j];
            }
        }

        $form = $this->createForm(ContactType::class);

        return $this->render('home/index.html.twig', [
            'aside_menu' => $asideMenu,
            'form' => $form
        ]);
    }

    /**
     * @Route("/activities", name="activities", methods={"GET"})
     */
    public function activities()
    {
        return $this->render('home/activities.html.twig');
    }

    /**
     * @Route("/legal_mentions", name="legal_mentions", methods={"GET"})
     */
    public function mentions()
    {
        return $this->render('home/mentions.html.twig');
    }

    /**
     * @Route("/partners", name="partners", methods={"GET"})
     */
    public function partners(Request $request)
    {
        $locale = $request->getLocale();
        $decodedPartners = array();

        $partners = json_decode(file_get_contents(sprintf('%s/../../Resources/public/partners.json', __DIR__)), true);

        foreach ($partners as $partner) {
            $partner['description'] = $partner['description'][$locale];
            $decodedPartners[] = $partner;
        }

        return $this->render('home/partners.html.twig', [
            'partners' => $decodedPartners,
        ]);
    }

    /**
     * @Route("/courses", name="courses", methods={"GET"})
     */
    public function courses(Request $request)
    {
        $locale = $request->getLocale();

        $courses = [];
        $courses[0] = "html5-css3";
        $courses[1] = "oop-uml-scm";
        $courses[2] = "symfony2";
        $courses[3] = "wordpress";
        return $this->render('home/courses.html.twig', [
            'courses' => $courses
        ]);
    }

    /**
     * @Route("/sitemap", name="sitemap", methods={"GET"})
     */
    public function sitemap()
    {
        return $this->render('home/sitemap.html.twig');
    }
}
