<?php

namespace Generator;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\RouteCollection;


/**
 * I18nRouteGenerator.
 *
 * @author Brahim Boukoufallah <brahim.boukoufallah@idci-consulting.fr>
 *
 */
class I18nRouteGenerator
{
    /**
     * @var array
     */
    private $languages;

    /**
     * @var RouteCollection
     */
    private $routes;

    /**
     * @var UrlGenerator
     */
    private $urlGenerator;

    /**
     * Constructor.
     *
     * @param RouteCollection $routes       The route to internationalize
     * @param UrlGenerator    $urlGenerator The url generator
     */
    public function __construct(RouteCollection $routes, UrlGenerator $urlGenerator)
    {
        $this->routes       = $routes;
        $this->urlGenerator = $urlGenerator;
    }

    /**
     * Generate international routes.
     *
     * @param Request $request The request
     *
     * @return array
     */
    public function generate(Request $request)
    {
        $path       = str_replace($request->getBaseUrl(), '', $request->getRequestUri());
        $pathChunks = explode('?', $path);

        $requestContext = new RequestContext($request->getRequestUri());
        $matcher = new UrlMatcher($this->routes, $requestContext);
        $route   = $matcher->match($pathChunks[0]);

        if (!in_array('_locale', array_keys($route))) {
            // TODO: Reflechir sur quoi faire s'il n'y a pas de locale
        }

        $routeName = $route['_route'];

        $generatedRoutes = $this->doGeneration($routeName);

        return $generatedRoutes;
    }

    /**
     * Set languages
     *
     * @param $languages
     */
    public function setLanguages($languages)
    {
        $this->languages = $languages;
    }

    /**
     * Do internationalization generation
     *
     * @param $routeName
     * @return array
     */
    private function doGeneration($routeName)
    {
        $routes = array();

        foreach ($this->languages as $language) {
            $generatedRoute = $this->urlGenerator->generate($routeName, array('_locale' => $language));

            $routes[$language] = $generatedRoute;
        }

        return $routes;
    }


   /* // TODO: Code to use to implement teh service
    public function CodeToImplements()
    {
        $path = str_replace($request->getBaseUrl(), '', $request->getRequestUri());
        $pathPart = explode('?', $path);
        $requestContext = new \Symfony\Component\Routing\RequestContext($request->getRequestUri());
        $matcher = new \Symfony\Component\Routing\Matcher\UrlMatcher($app['routes'], $requestContext);
        $route = $matcher->match($pathPart[0]);
        if (in_array("_locale", array_keys($route))) {
            $routeName = $route["_route"];
        } else {
            die('pas de local');
        }

        var_dump($app['url_generator']->generate($routeName, array('_locale' => 'fr')));die;
    }*/
}