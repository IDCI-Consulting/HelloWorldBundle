<?php

namespace App\Controller\Website;

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
     * @Method({"GET"})
     */
    public function home()
    {
        return $this->render('home/index.html.twig');
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
    public function courses()
    {
        return $this->render('home/courses.html.twig');
    }

    /**
     * @Route("/sitemap", name="sitemap", methods={"GET"})
     */
    public function sitemap()
    {
        return $this->render('home/sitemap.html.twig');
    }
}
