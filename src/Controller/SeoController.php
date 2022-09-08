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
        $hostname = $request->getHost();
        $availableLanguages = [];
        $urls = [];
        $routes = $this->container->get('router')->getRouteCollection();

        foreach ($routes as $key => $route) {
            $urls[] = [
                'loc' => $this->generateUrl($key)
            ];
        }


        
        // $urls[] = [
        //     'loc' => $this->generateUrl('app_homepage'),
        //     'priority' => 1
        // ];
        // $urls[] = [
        //     'loc' => $this->generateUrl('app_courses_list')
        // ];

        $response = new Response(
            $this->renderView('seo/sitemap.xml.twig', [
                'urls' => $urls,
                'hostname' => $hostname
            ])
        );
        $response->headers->set('Content-Type', 'text/xml');

        return $response;

        // foreach ($app['i18n_route_generator.languages'] as $locale => $language) {
        //     $availableLanguages[] = $locale;
        // }

        // $locale = $request->getPreferredLanguage($availableLanguages);

        // $app['sitemap_manager']->addConfiguration('_locale', $locale);

        // if (($key = array_search($locale, $availableLanguages)) !== false) {
        //     unset($availableLanguages[$key]);
        // }

        // $app['sitemap_manager']->addConfiguration('other_langs', $availableLanguages);

        // $urls = $app['sitemap_manager']->build();
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